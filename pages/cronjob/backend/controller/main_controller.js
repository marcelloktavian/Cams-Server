const Jurnal = require("../model/jurnal")
const MainProcess = require("../model/main")

const getProcess = async (req,res) => {
    const jmodel = new Jurnal()

    let result = []

    // tambahkan juga, dan pastikan setiap field yang di ambil sama untuk setiap proses
    await jmodel.getData().then((d) => {
        result.push(...d)
    })

    return res.json({data:result})
}

const startProcess = (req,res) => {
    const main = new MainProcess()

    main.processCron(req.params.name)
}

module.exports = {
    getProcess,
    startProcess
}