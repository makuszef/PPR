using System;
using System.Net;
using System.Net.Sockets;
using System.Text;
using System.IO;
using System.Collections.Generic;
class klient
{
    public static void Main(string[] args)
    {
        int maxImageSize = 1024^3;
        //czytaj plik
        if(args.Length == 0) {
            Console.WriteLine("No command-line arguments provided.");
            Environment.Exit(1);
        }
        // Path to the JPEG file
        string filePath = args[0].ToString();
        TextReader tIn = Console.In;
        byte[] ImageBytes = new byte[maxImageSize];

        Stream inputStream = Console.OpenStandardInput();
        int readBytes = inputStream.Read(ImageBytes, 0, maxImageSize);
        byte[] copiedBytes = new byte[readBytes];
        Array.Copy(ImageBytes, 0, copiedBytes, 0, readBytes);
        Console.WriteLine("Read bytes {0}", readBytes);

        //for arguments
        FileInfo fileInfo = new FileInfo(filePath);
        if (fileInfo.Exists)
        {
            // Get the size of the file in bytes
            long fileSizeInBytes = fileInfo.Length;
            if (fileSizeInBytes > maxImageSize) {
                Console.WriteLine("Image size is greater than 100MB");
            }
        }
        else
        {
            Console.WriteLine("File does not exist.");
        }
        // Read the bytes from the file
        byte[] imageBytes = File.ReadAllBytes(filePath);
        try
        {
            Int32 port = 13001;
            TcpClient client = new TcpClient("127.0.0.1", port);
            NetworkStream stream = client.GetStream();
            stream.Write(copiedBytes, 0, copiedBytes.Length);
/*
            // String to store the response ASCII representation.
            String responseData = String.Empty;

            // Read the first batch of the TcpServer response bytes.
            Int32 bytes = stream.Read(data, 0, data.Length);
            responseData = Encoding.ASCII.GetString(data, 0, bytes);
            Console.WriteLine("Received: {0}", responseData);
*/
            // Close everything.
            stream.Close();
            client.Close();
        }
        catch (ArgumentNullException e)
        {
            Console.WriteLine("ArgumentNullException: {0}", e);
        }
        catch (SocketException e)
        {
            Console.WriteLine("SocketException: {0}", e);
            writeToFile(e);
        }

        Console.WriteLine("\n Press Enter to continue...");
        Console.Read();
    }
    static void writeToFile(Exception ex) {
        DateTime timestamp = DateTime.Now;
        string filePath = "exception_log";
        filePath += ".txt";
        if (File.Exists(filePath))
        {
            using (StreamWriter writer = new StreamWriter(filePath, true))
            {
                writer.WriteLine("Exception occurred at: " + timestamp);
                writer.WriteLine("Exception message: " + ex.Message);
                writer.WriteLine("Stack trace: " + ex.StackTrace);
            }
        }
        else {
            using (StreamWriter writer = File.CreateText(filePath))
            {
                // Write the exception message to the file
                writer.WriteLine("Exception occurred at: " + timestamp);
                writer.WriteLine("Exception message: " + ex.Message);
                writer.WriteLine("Stack trace: " + ex.StackTrace);
            }
        }
    }
}
