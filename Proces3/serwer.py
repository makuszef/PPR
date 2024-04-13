from xmlrpc.server import SimpleXMLRPCServer
from xmlrpc.server import SimpleXMLRPCRequestHandler

# Restrict to a particular path.
class RequestHandler(SimpleXMLRPCRequestHandler):
    rpc_paths = ('/RPC2',)

# Create server
server = SimpleXMLRPCServer(("localhost", 8000),
                            requestHandler=RequestHandler, logRequests=True)
server.register_introspection_functions()

# Define a function to be called remotely
def add(x, y):
    print("X:" + x)
    return x + y

# Register the function with a different name
server.register_function(add, 'add_numbers')

# Run the server's main loop
print("XML-RPC server is running on localhost:8000...")
server.serve_forever()
