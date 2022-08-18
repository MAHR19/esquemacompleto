from zeep import Client
import logging
import base64
from lxml import etree
from zeep.plugins import HistoryPlugin
 
logging.basicConfig(level=logging.INFO)
logging.getLogger('zeep.client').setLevel(logging.DEBUG)
logging.getLogger('zeep.transport').setLevel(logging.DEBUG)
logging.getLogger('zeep.xsd.schema').setLevel(logging.DEBUG)
logging.getLogger('zeep.wsdl').setLevel(logging.DEBUG)
 
 
username = "mhurtado@finkok.com.mx"
password = "Samydean26714+"
 
RFC = 'XIQB891116QE4'
status = 'A' 
 
# Read the x509 certificate file on PEM format and encode it on base64
cer_path = "/home/mhurtado/RegistrationTutorial/ActualizarCSD/CSD/certificadoUpt.cer" 
file = open(cer_path, "rb")
cer = (file.read())
 
# Read the Encrypted Private Key file on PEM format and encode it on base64
key_path = "/home/mhurtado/RegistrationTutorial/ActualizarCSD/CSD/llaveUpt.key" 
file = open(key_path, "rb")
key = (file.read())
 
passphrase = '12345678a'
 
url = "https://demo-facturacion.finkok.com/servicios/soap/registration.wsdl"
history = HistoryPlugin()
client = Client(wsdl = url, plugins = [history])
 
resultado = client.service.edit(username,password,RFC,status, cer, key, passphrase)
print(resultado)
 
archivo = open("edit.txt", "w")
archivo.write(str(resultado))
archivo.close()
 
# Get SOAP Request
request = etree.tostring(history.last_sent["envelope"])
req_file = open('requestUpdate.xml', 'w') 
req_file.write(request.decode("UTF-8"))
req_file.close()
 
# Get SOAP Response 
response = etree.tostring(history.last_received["envelope"])
res_file = open('responseUpdate.xml', 'w')
res_file.write(response.decode("UTF-8"))
res_file.close()