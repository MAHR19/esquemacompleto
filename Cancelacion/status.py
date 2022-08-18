from zeep import Client
import logging
import base64
from lxml import etree
from zeep.plugins import HistoryPlugin
 
logging.basicConfig(level=logging.INFO)
logging.getLogger('zeep.client').setLevel(logging.DEBUG)
#logging.getLogger('zeep.transport').setLevel(logging.DEBUG)
#logging.getLogger('zeep.xsd.schema').setLevel(logging.DEBUG)
#logging.getLogger('zeep.wsdl').setLevel(logging.DEBUG)
 
username='mhurtado@finkok.com.mx' # usuario proporcionado por la plataforma de Finkok. Tipo String
password='Samydean26714+' # La lcontraseña proporcionada por la plataforma Finkok.Tipo String
 
taxpayer_id='EKU9003173C9'# El rfc emisor de las facturas a consultar Tipo String 
rtaxpayer_id='CUSC850516316' #El rfc receptorde los CFDI a consultar Tipo String
uuid='54F7AA1A-D156-59C6-8827-56189AE8ED0D' # El UUID del CFDI a consultar  Tipo Sring
total='7510.77' # El valor del aributo total del CFDI
 
history = HistoryPlugin()
url = "https://demo-facturacion.finkok.com/servicios/soap/cancel.wsdl"
client = Client(wsdl = url, plugins = [history])#location=url
 
contenido =  client.service.get_sat_status(username, password, taxpayer_id, rtaxpayer_id, uuid, total)
print(contenido)
 
 
# Get save print data
archivo = open("get_sat_statusInvoiceCancelA.txt","w")
archivo.write(str(contenido))
archivo.close()
 
# Get SOAP Request
request = etree.tostring(history.last_sent["envelope"])
req_file = open('requeststatusInvoiceCancelA.xml', 'w') 
req_file.write(request.decode("UTF-8") )
req_file.close()
 
# Get SOAP Response 
response = etree.tostring(history.last_received["envelope"])
res_file = open('responsestatusInvoiceCancelA.xml', 'w')
res_file.write(response.decode("UTF-8") )
res_file.close()