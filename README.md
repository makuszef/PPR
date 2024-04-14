# Communication between 3 processes
Tested in Linux Kali
Image is jpg format
Process 1 reads image and sends it to Process 2 (Tcp Socket communication)
Process 2 recievs image and sends it to Process 3 (XML RPC communication)
Process 3 recievs image and converts it to PNG format, saves to disk
