// dependency
const express = require('express');
const cron = require("node-cron");
require("dotenv").config()
const cors = require("cors")
const path = require("path")

// module 
const router = require("./routes/routes");
const MainProcess = require('./model/main');

// main process or crons every midnight (22.00)
cron.schedule("0 22 * * *",() => {
    const mainprocess = new MainProcess()
    mainprocess.processCrons()
})


const app = express()

app.use(express.json())
app.use(cors())

app.use(express.static(path.join(__dirname,'dist')))

app.use("/api",router)

app.get("*",(req,res) => {
    res.sendFile(path.resolve(__dirname,'dist','index.html'))
})

app.listen(process.env.PORT || 3000,() => {
    console.log(`SERVER RUNNING ON PORT : ${process.env.PORT || 3000}`);
})