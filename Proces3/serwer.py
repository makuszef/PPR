from xmlrpc.server import SimpleXMLRPCServer
from xmlrpc.server import SimpleXMLRPCRequestHandler
import base64
# Restrict to a particular path.
class RequestHandler(SimpleXMLRPCRequestHandler):
    rpc_paths = ('/RPC2',)

# Create server
server = SimpleXMLRPCServer(("localhost", 8000),
                            requestHandler=RequestHandler, logRequests=True)
server.register_introspection_functions()

# Define a function to be called remotely
def add(x, y):
    return x + y
def handleImage(image):
    image_data = base64.b64decode(image)
    image_path = "decoded_image.jpg"
    with open(image_path, "wb") as image_file:
        image_file.write(image_data)
    return "image"
# Register the function with a different name
server.register_function(add, 'add_numbers')
server.register_function(handleImage, 'handleImage')

# Run the server's main loop
print("XML-RPC server is running on localhost:8000...")
server.serve_forever()
