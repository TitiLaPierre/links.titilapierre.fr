const fs = require("fs")
const path = require("path")
const sass = require("sass-embedded")
const chokidar = require("chokidar")

const directories = ["global/scss", "global/js", "src"]
const scssPath = "global/scss"
const cssPath = "css"
const jsPath = "js"

function compile(sourcePath, targetPath) {
    const tempPath = sourcePath.replace(".scss", ".temp.scss")
    let error;
    fs.mkdirSync(path.dirname(targetPath), { recursive: true })
    if (sourcePath.endsWith(".scss")) {
        targetPath = targetPath.replace(".scss", ".css")
        try {
            let content = fs.readFileSync(sourcePath, "utf8")
            const regex = /@(?<type>use|forward|import)\s+"~(?<path>[\w(\/|\\|)]+)"/gm
            content = content.replace(regex, (_, type, importPath) => {
                const newPath = path.relative(path.dirname(sourcePath), importPath).replace(/\\/g, "/")
                return `@${type} "${newPath}"`
            })
            fs.writeFileSync(tempPath, content, { mode: 0o644 })
            const result = sass.compile(tempPath)
            fs.writeFileSync(targetPath, result.css.toString())
        } catch (compileError) {
            error = compileError
        } finally {
            if (fs.existsSync(tempPath)) fs.unlinkSync(tempPath)
        }
    } else {
        fs.copyFileSync(sourcePath, targetPath)
    }
    if (error)
        console.error(`Error compiling ${sourcePath}:`, error)
    else
        console.log(`Compiled ${sourcePath} to ${targetPath}`)
}

function search(directory, extension) {
    let files = []
    fs.readdirSync(directory).forEach((file) => {
        const filePath = path.join(directory, file)
        const stats = fs.statSync(filePath)
        if (stats.isDirectory()) {
            files = files.concat(search(filePath, extension))
        } else if (filePath.endsWith(extension) && !file.endsWith(".temp.scss")) {
            files.push(filePath)
        }
    })
    return files
}

async function initiliaze() {
    const promises = []
    for (const directory of [scssPath, cssPath, jsPath]) {
        fs.mkdirSync(directory, { recursive: true })
        fs.readdirSync(directory).forEach((file) => {
            if (file.endsWith(".css") || file.endsWith(".js")) {
                fs.unlinkSync(path.join(directory, file))
            }
        })
    }
    for (const directory of directories) {
        for (const [extension, targetPath] of [[".scss", cssPath], [".js", jsPath]]) {
            const files = search(directory, extension)
            for (const file of files) {
                const relPath = path.relative(directory, file)
                const destinationPath = path.join(targetPath, relPath)
                promises.push(compile(file, destinationPath))
            }
        }
        chokidar.watch(directory).on("change", (file) => {
            const relPath = path.relative(directory, file)
            let destination
            
            if (file.endsWith(".scss")) destination = path.join(cssPath, relPath)
            else if (file.endsWith(".js")) destination = path.join(jsPath, relPath)
                
            if (destination) compile(file, destination)
        })
        chokidar.watch(directory).on("add", (file) => {
            const relPath = path.relative(directory, file)
            let destination
            
            if (file.endsWith(".scss")) destination = path.join(cssPath, relPath)
            else if (file.endsWith(".js")) destination = path.join(jsPath, relPath)
                
            if (destination && !file.endsWith(".temp.scss")) compile(file, destination)
        })
        chokidar.watch(directory).on("unlink", (file) => {
            const relPath = path.relative(directory, file)
            let destination
            
            if (file.endsWith(".scss")) destination = path.join(cssPath, relPath)
            else if (file.endsWith(".js")) destination = path.join(jsPath, relPath)
                
            if (destination && fs.existsSync(destination)) fs.unlinkSync(destination)
        })
    }
    await Promise.all(promises)
    console.log("Watching for changes...")
}

initiliaze()