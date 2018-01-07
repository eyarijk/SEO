var io = require('socket.io')(6010);

io.on('connection', function(socket){
    console.log('a user connected');
    socket.on('disconnect', function(){
        console.log('user disconnected');
    });
});
io.on('connection', function(socket){
    socket.on('chat message', function(msg){
        socket.broadcast.emit('chat message', msg);
    });
});