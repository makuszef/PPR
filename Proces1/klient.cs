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
        try {
            string imageName = "Nazwa.jpg";
            string serverIP = "127.0.0.1";
            Int32 port = 13001;
            Int32 portUDP = 14001;
            int maxImageSize = 1024^3;
            //czytaj plik
            if(args.Length == 0) {
                Console.WriteLine("No command-line arguments provided.");
                Environment.Exit(1);
            }
            // Path to the JPEG file
            string filePath = args[0].ToString();
            imageName = filePath.Split(".")[0];
            if (args[0].ToString() == "E") {
                throw new Exception("Exeption.");
            }
            //read input
            /*
            TextReader tIn = Console.In;
            byte[] ImageBytes = new byte[maxImageSize];

            Stream inputStream = Console.OpenStandardInput();
            int readBytes = inputStream.Read(ImageBytes, 0, maxImageSize);
            byte[] copiedBytes = new byte[readBytes];
            Array.Copy(ImageBytes, 0, copiedBytes, 0, readBytes);
            Console.WriteLine("Read bytes {0}", readBytes);
            */
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

            TcpClient client = new TcpClient(serverIP, port);
            NetworkStream stream = client.GetStream();
            stream.Write(imageBytes, 0, imageBytes.Length);
            // Close everything.
            stream.Close();
            client.Close();

            using (UdpClient Udpclient = new UdpClient())
            {
                try
                {
                    byte[] data = Encoding.UTF8.GetBytes(imageName);
                    // Send the message to the server
                    Udpclient.Send(data, data.Length, serverIP, portUDP);
                }
                catch (Exception e)
                {
                    Console.WriteLine("Error: " + e.Message);
                    writeToFile(e);
                }
            }
            Console.WriteLine("\n Press Enter to continue...");
            Console.Read();
        }
        catch (Exception e) {
            writeToFile(e);
        }
    }
    static void writeToFile(Exception ex) {
        DateTime timestamp = DateTime.Now;
        string filePath = "exception_log";
        filePath += ".txt";
        string Message = "\nException occurred at:" + timestamp + "\nException message: " + ex.Message + "\nStack trace: " + ex.StackTrace;
        if (File.Exists(filePath))
        {
            using (StreamWriter writer = new StreamWriter(filePath, true))
            {
                writer.WriteLine(Message);
            }
        }
        else {
            using (StreamWriter writer = File.CreateText(filePath))
            {
                writer.WriteLine(Message);
            }
        }
    }
}
