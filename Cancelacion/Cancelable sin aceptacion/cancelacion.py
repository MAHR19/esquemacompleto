from suds.client import Client
import logging
import base64
 
logging.basicConfig(level=logging.INFO)
logging.getLogger('suds.client').setLevel(logging.DEBUG)
 
username = 'mhurtado@finkok.com.mx' # El usuario proporcionado por la plataforma Finkok
password = 'Samydean26714+' # Contraseña proporcionada por la plataforma Finkok
taxpayer_id = 'EKU9003173C9' #El RFC del Receptor
cer_path = open("/home/mhurtado/RegistrationTutorial/Cancelacion/DOCS/certificado.pem", "rb").read()
cer_file = base64.b64encode(cer_path)
cer_file = cer_file.decode()
 
key_path = open("/home/mhurtado/RegistrationTutorial/Cancelacion/DOCS/llave.enc", "rb").read()
key_file = base64.b64encode(key_path)
key_file=key_file.decode()
 
 
url = "https://demo-facturacion.finkok.com/servicios/soap/cancel.wsdl"
client = Client(url,cache=None)
 
invoices_obj = client.factory.create("ns0:UUID")
invoices_obj._UUID='22073F30-01E7-5906-92F9-29CE02B15EAD'
invoices_obj._FolioSustitucion=''
invoices_obj._Motivo='03'
UUIDS_list = client.factory.create("ns0:UUIDArray")
 
UUIDS_list.UUID.append(invoices_obj)
 
result = client.service.cancel(UUIDS_list, username, password, taxpayer_id, cer_file, key_file)
 
last_request = client.last_sent()
req_file = open('/home/mhurtado/RegistrationTutorial/Cancelacion/Cancelable sin aceptacion/requestCancelCrp20.xml', 'w')
req_file.write(str(last_request))
req_file.close()
 
last_response = client.last_received()
res_file = open('/home/mhurtado/RegistrationTutorial/Cancelacion/Cancelable sin aceptacion/responseCancelCrp20.xml', 'w')
res_file.write(str(last_response))
res_file.close()