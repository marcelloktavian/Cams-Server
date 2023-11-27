const response = (res,msg = "",data = {},status = 200) => {
    return res.status(status).json({msg,data:data})
}

module.exports = {response}