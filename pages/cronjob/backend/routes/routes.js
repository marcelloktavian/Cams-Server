const express = require('express');
const router = express.Router()
const path = require("path")

router.get("/cron",require("../controller/main_controller").getProcess)
router.post("/start/:name",require("../controller/main_controller").startProcess)

module.exports = router