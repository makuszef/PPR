# Communication Between Three Processes

This document describes the communication workflow between three processes tested in Kali Linux.

## Overview

- **Process 1**: Reads an image and sends it to Process 2 via TCP socket communication.
- **Process 2**: Receives the image from Process 1 and forwards it to Process 3 using XML-RPC communication.
- **Process 3**: Receives the image, converts it to PNG format, and saves it to disk.

## Steps

1. **Process 1: Read and Send Image**
   - **Input**: Image in JPG format.
   - **Action**: Reads the JPG image and sends it to Process 2 using TCP socket communication.

2. **Process 2: Receive and Forward Image**
   - **Input**: Image received from Process 1.
   - **Action**: Receives the JPG image via TCP socket, then forwards it to Process 3 using XML-RPC communication.

3. **Process 3: Receive, Convert, and Save Image**
   - **Input**: Image received from Process 2.
   - **Action**: Receives the JPG image via XML-RPC, converts it to PNG format, and saves the converted image to disk.

# Sequence of Invoking Processes

Follow these steps to invoke the processes in the correct order:

1. **Start Process 1**
   - Compile the C# client application.
     ```bash
     mcs klient.cs
     ```
   - Run the compiled executable, specifying the file path to the image.
     ```bash
     ./klient.exe file_path_to_image
     ```

2. **Start Process 2**
   - Execute the PHP script that handles communication for Process 2.
     ```bash
     php klient2.php
     ```

3. **Start Process 3**
   - Run the Python script that processes the image.
     ```bash
     python3 serwer.py
     ```
<p align="center">
  Watch it on Youtube
</p>
<p align="center">
  <a href="https://youtu.be/1SP2MZhuVkE">
    <img src="https://img.youtube.com/vi/1SP2MZhuVkE/0.jpg" alt="Watch this video on YouTube" width="600">
  </a>
</p>



