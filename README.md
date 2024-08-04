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
