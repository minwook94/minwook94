const express = require('express');
const app = express();


app.get('/test', function(req, res) {
    res.send("hello world!!!!!!!!!!!");
});


var server = app.listen(55420);