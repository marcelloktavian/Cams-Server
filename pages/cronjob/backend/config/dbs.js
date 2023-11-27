const mysql = require('mysql2/promise');
require("dotenv").config()

// define connection
const db1 = mysql.createPool({
    host:process.env.DB_HOST,
    database:process.env.DB_NAME,
    user:process.env.DB_USER,
    password:process.env.DB_PASSWORD
})

// export
module.exports = {
    db1
}