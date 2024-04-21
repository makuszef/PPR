from xmlrpc.server import SimpleXMLRPCServer
from xmlrpc.server import SimpleXMLRPCRequestHandler
import base64
import datetime
import traceback
from PIL import Image
# Restrict to a particular path.
class RequestHandler(SimpleXMLRPCRequestHandler):
    rpc_paths = ('/RPC2',)
def add(x, y):
    return x + y
def handleImage(image):
    image_data = base64.b64decode(image)
    image_path = "decoded_image.jpg"
    with open(image_path, "wb") as image_file:
        image_file.write(image_data)
    #im = Image.open(r"decoded_image.jpg")
    #im.save(r"decoded_image.png")
    png_image = Image.open(BytesIO(image_data))
    png_image.convert("RGB").save(jpeg_image_path, "JPEG")
    return "image"

try:
    #raise Exception("This is an example exception message.")
    server = SimpleXMLRPCServer(("localhost", 8000),requestHandler=RequestHandler, logRequests=True)
    server.register_introspection_functions()

    server.register_function(add, 'add_numbers')
    server.register_function(handleImage, 'handleImage')

    # Run the server's main loop
    print("XML-RPC server is running on localhost:8000...")
    server.serve_forever()
except Exception as ex:
    dateTime = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S");
    file_path = "exception_log"
    file_path += ".txt"
    # Format the exception message
    exception_message = f"Exception occurred at: {dateTime}\n"
    exception_message += f"Exception message: {str(ex)}\n"
    exception_message += f"Stack trace:\n{traceback.format_exc()}"

    # Write the exception message to the file
    with open(file_path, "a") as file:
        file.write(exception_message)
