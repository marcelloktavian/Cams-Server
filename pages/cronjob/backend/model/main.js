const Jurnal = require("./jurnal");

class MainProcess  {
     #jurnal = new Jurnal()

    processCron(process) {
        if (process == "jurnal") {
            this.#jurnal.insertCron()
        }
    }

    processCrons() {
        this.#jurnal.insertCron()
    }
}

module.exports = MainProcess 