using System;
using System.Net;
using System.Net.Sockets;
using System.Text;
using System.IO;
class klient
{
    public static void Main(string[] args)
    {
        //czytaj plik
        if(args.Length == 0) {
            Console.WriteLine("No command-line arguments provided.");
            Environment.Exit(1);
        }
        // Path to the JPEG file
        string filePath = args[0].ToString();

        // Read the bytes from the file
        byte[] imageBytes = File.ReadAllBytes(filePath);
        int maxImageSize = 100000000;
        if (imageBytes.Length > maxImageSize) {
            Console.WriteLine("Image size is greater than 100MB");
        }
        string base64String = Convert.ToBase64String(imageBytes);
        try
        {
            Int32 port = 13001;
            TcpClient client = new TcpClient("127.0.0.1", port);
            NetworkStream stream = client.GetStream();
            stream.Write(imageBytes, 0, imageBytes.Length);
            Console.WriteLine("Sent: {0}", base64String);
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
        }

        Console.WriteLine("\n Press Enter to continue...");
        Console.Read();
    }
}
