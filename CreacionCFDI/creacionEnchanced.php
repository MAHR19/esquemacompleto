<?php
 
satxmlsv40(false,"","","");
 
// {{{  satxmlsv40
function satxmlsv40($arr, $edidata=false, $dir="",$nodo="",$addenda="") {
global $xml, $cadena_original, $sello, $texto, $ret;
error_reporting(E_ALL & ~(E_WARNING | E_NOTICE));
satxmlsv40_genera_xml($arr,$edidata,$dir,$nodo,$addenda);
satxmlsv40_genera_cadena_original();
satxmlsv40_sella($arr);
$ret = satxmlsv40_termina($arr,$dir);
return $ret;
}
 
// {{{  satxmlsv40_genera_xml
function satxmlsv40_genera_xml($arr, $edidata, $dir,$nodo,$addenda) {
global $xml, $ret;
$xml = new DOMdocument("1.0","UTF-8");
satxmlsv40_generales($arr, $edidata, $dir,$nodo,$addenda);
satxmlsv40_InformacionGlobal($arr, $edidata, $dir,$nodo,$addenda);
satxmlsv40_relacionados($arr, $edidata, $dir,$nodo,$addenda);
satxmlsv40_emisor($arr, $edidata, $dir,$nodo,$addenda);
satxmlsv40_receptor($arr, $edidata, $dir,$nodo,$addenda);
satxmlsv40_conceptos($arr, $edidata, $dir,$nodo,$addenda);
satxmlsv40_impuestos($arr, $edidata, $dir,$nodo,$addenda);
 
}
// }}}
 
// {{{  Datos generales del Comprobante
function satxmlsv40_generales($arr, $edidata, $dir,$nodo,$addenda) {
global $root, $xml;
//$date = date('d-m-y h:i:s');
$root = $xml->createElement("cfdi:Comprobante");
$root = $xml->appendChild($root);
 
satxmlsv40_cargaAtt($root, array("xmlns:cfdi"=>"http://www.sat.gob.mx/cfd/4",
                          "xmlns:xsi"=>"http://www.w3.org/2001/XMLSchema-instance",
                          "xsi:schemaLocation"=>"http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd"
                         )
                     );
 
satxmlsv40_cargaAtt($root, array("Version"=>"4.0",
                      "Serie"=>"A",
                      "Folio"=>"167ABC",
                      //"fecha"=>satxmlsv40_xml_fech(date),
                      "Fecha"=>date("Y-m-d"). "T" .date("H:i:s"),
                      "Sello"=>"@",
                      "FormaPago"=>"01      ",
                      "NoCertificado"=>no_Certificado(),
                      "Certificado"=>"@",
                      //"CondicionesDePago"=>"CONDICIONES",
                      "SubTotal"=>"6474.81",
                      //"Descuento"=>"22500.00",
                      "Moneda"=>"MXN",
                      "TipoCambio"=>"1",
                      "Total"=>"7510.77",
                      "TipoDeComprobante"=>"I",
                      "Exportacion"=> "01",
                      "MetodoPago"=> "PUE",
                      "LugarExpedicion"=>"58000",
                   )
                );
}
 
// Datos de InformacionGlobal
function satxmlsv40_InformacionGlobal($arr, $edidata, $dir,$nodo,$addenda) {
    global $root, $xml;
        $iglobal = false; // indicamos si el nodo existira dentro del XML (true= existe, false = se omite)
        if ($iglobal == true)
        {
            $iglobal = $xml->createElement("cfdi:InformacionGlobal");
            $iglobal = $root->appendChild($iglobal);
            satxmlsv40_cargaAtt($iglobal, array("Periodicidad"=>"01",
                                          "Meses"=>"02",
                                          "Año"=>"2022",
                                        )
                                    );
        }
}
 
// {{{ Datos de documentos relacionados
    function satxmlsv40_relacionados($arr, $edidata, $dir,$nodo,$addenda) {
    global $root, $xml;
 
        $cfdis = $xml->createElement("cfdi:CfdiRelacionados");
        $cfdis = $root->appendChild($cfdis);
        satxmlsv40_cargaAtt($cfdis, array("TipoRelacion"=>"04"));
        $cfdi = $xml->createElement("cfdi:CfdiRelacionado");
        $cfdi = $cfdis->appendChild($cfdi);
        satxmlsv40_cargaAtt($cfdi, array("UUID"=>"54F7AA1A-D156-59C6-8827-56189AE8ED0D"));
 
}
// }}}
 
 
// Datos del Emisor
function satxmlsv40_emisor($arr, $edidata, $dir,$nodo,$addenda) {
  global $root, $xml;
  $emisor = $xml->createElement("cfdi:Emisor");
  $emisor = $root->appendChild($emisor);
  satxmlsv40_cargaAtt($emisor, array("Rfc"=>"EKU9003173C9",
                                     "Nombre"=>"ESCUELA KEMPER URGATE",
                                     "RegimenFiscal"=>"601",
                                    )
                                );
}
 
// Datos del Receptor
 
function satxmlsv40_receptor($arr, $edidata, $dir,$nodo,$addenda) {
global $root, $xml;
$receptor = $xml->createElement("cfdi:Receptor");
$receptor = $root->appendChild($receptor);
satxmlsv40_cargaAtt($receptor, array("Rfc"=>"CUSC850516316",
                                     "Nombre"=>"CESAR OSBALDO CRUZ SOLORZANO",
                                     "UsoCFDI"=>"S01",
                                     "RegimenFiscalReceptor"=>"616",
                                     "DomicilioFiscalReceptor"=>"45638",
                      )
                  );
}
 
// 
// Detalle de los conceptos/productos de la factura
function satxmlsv40_conceptos($arr, $edidata, $dir,$nodo,$addenda) {
global $root, $xml;
$conceptos = $xml->createElement("cfdi:Conceptos");
$conceptos = $root->appendChild($conceptos);
$conceptos_array=xml_to_array($xml);
print(sizeof($conceptos_array));
for ($i=1; $i<=sizeof($conceptos_array); $i++) {
    $concepto = $xml->createElement("cfdi:Concepto");
    $concepto = $conceptos->appendChild($concepto);
    satxmlsv40_cargaAtt($concepto, array(
                              "ClaveProdServ" => "80131500",
                              //"NoIdentificacion"=>"NO123"
                              "Cantidad" => "1.00",
                              "ClaveUnidad" => "CE",
                              "NoIdentificacion" => "00001",
                              "Unidad" => "CE",
                              "Descripcion" => "ARRENDAMIENTO DE JUAREZ PTE 108-A",
                              "ValorUnitario" => "6474.81",
                              "Importe"=>"6474.81",
                              //"Descuento" => "22500.00",
                              "ObjetoImp" => "02"
        )
    );
    $impuestos = true; // indicamos si el nodo existirá dentro del XML (true= existe, false = se omite)
    if ($impuestos == true) 
    {
        $impuestos = $xml->createElement("cfdi:Impuestos");
        $impuestos = $concepto->appendChild($impuestos);
 
        $traslados = true;
        if ($traslados = true) 
        {
            $traslados = $xml->createElement("cfdi:Traslados");
            $traslados = $impuestos->appendChild($traslados);
            $traslado = $xml->createElement("cfdi:Traslado");
            $traslado = $traslados->appendChild($traslado);
            satxmlsv40_cargaAtt(
                $traslado,
                array(
                    "Base" => "6474.81",
                    "Impuesto" => "002",
                    "TipoFactor" => "Tasa",
                    "TasaOCuota" => "0.160000",
                    "Importe" => "1035.96",
                )
            );
        }
 
        $retenciones = false; // indicamos si el nodo existira dentro del XML (true= existe, false = se omite)
        if ($retenciones == true) 
        {
            $retenciones = $xml->CreateElement("cfdi:Retenciones");
            $retenciones = $impuestos->appendChild($retenciones);
            $retencion = $xml->CreateElement("cfdi:Retencion");
            $retencion = $retenciones->appendChild($retencion);
            satxmlsv40_cargaAtt(
                $retencion,
                array(
                    "Base" => "1000.00",
                    "Importe" => "40.00",
                    "Impuesto" => "002",
                    "TasaOCuota" => "0.040000",
                    "TipoFactor" => "Tasa",
 
                )
            );
        }
    }
  }
}
// 
// Impuesto (IVA)
function satxmlsv40_impuestos($arr, $edidata, $dir,$nodo,$addenda) {
global $root, $xml;
    $impuestos2 = true; // indicamos si el nodo existira dentro del XML (true= existe, false = se omite)
    if ($impuestos2 == true) 
    {
        $impuestos2 = $xml->CreateElement("cfdi:Impuestos");
        $impuestos2 = $root->appendChild($impuestos2);
        $impuestos2->SetAttribute("TotalImpuestosTrasladados","1035.96");
        //$impuestos2->SetAttribute("TotalImpuestosRetenidos","3599.99");
 
        $retenciones2 = false; // indicamos si el nodo existira dentro del XML (true= existe, false = se omite)
        if ($retenciones2 == true)
        {
 
            $retenciones2 = $xml->CreateElement("cfdi:Retenciones");
            $retenciones2 = $impuestos2->appendChild($retenciones2);
            $retencion2_array=xml_to_array($xml);
            for ($c = 1; $c <= sizeof($retencion2_array); $c++) {
                $retencion2 = $xml->CreateElement("cfdi:Retencion");
                $retencion2 = $retenciones2->appendChild($retencion2);
                satxmlsv40_cargaAtt($retencion2, array("Importe" => "40.00",
                                                       "Impuesto" => "002",
                ));
            }
        }
        $traslados2 = true; // indicamos si el nodo existira dentro del XML (true= existe, false = se omite)
 
        if ($traslados2 == true) 
        {
            $traslados2 = $xml->CreateElement("cfdi:Traslados");
            $traslados2 = $impuestos2->appendChild($traslados2);
            $traslados2_array=xml_to_array($xml);
            for ($c = 1; $c <= sizeof($traslados2_array); $c++) {
                $traslado2 = $xml->CreateElement("cfdi:Traslado");
                $traslado2 = $traslados2->appendChild($traslado2);
                satxmlsv40_cargaAtt($traslado2, array("Base" => "6474.81",
                                                      "Importe" => "1035.96",
                                                      "Impuesto" => "002",
                                                      "TasaOCuota" => "0.160000",
                                                      "TipoFactor" => "Tasa",
                                                    )
                                                );
            }
        }
 
    }
}
 
function no_Certificado()
{
    $cer = "/home/mhurtado/RegistrationTutorial/CreacionCFDI/CSD/certificado.cer"; //Ruta del archivo .cer
    $noCertificado = shell_exec("openssl x509 -inform DER -in " . $cer . " -noout -serial");
    $noCertificado = str_replace(' ', ' ', $noCertificado);
    $arr1 = str_split($noCertificado);
    $certificado = '';
    for ($i = 7; $i < count($arr1); $i++) {
        # code...
        if ($i % 2 == 0) {
            $certificado = ($certificado . ($arr1[$i]));
        }
    }
    return $certificado;
}
 
 
// genera_cadena_original
function satxmlsv40_genera_cadena_original() {
global $xml, $cadena_original;
$paso = new DOMDocument;
$paso->loadXML($xml->saveXML());
$xsl = new DOMDocument;
$file="http://www.sat.gob.mx/sitio_internet/cfd/4/cadenaoriginal_4_0/cadenaoriginal_4_0.xslt"; 
$xsl->load($file);
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl);
$cadena_original = $proc->transformToXML($paso);
$cadena_original = str_replace(array("\r", "\n"), '', $cadena_original);
#echo $cadena_original;
}
// 
 
// Calculo de sello
function satxmlsv40_sella($arr) {
global $root, $cadena_original, $sello;
$certificado = no_Certificado();
$file="/home/mhurtado/RegistrationTutorial/CreacionCFDI/CSD/llave.pem";      // Ruta al archivo
// Obtiene la llave privada del Certificado de Sello Digital (CSD),
//    Ojo , Nunca es la FIEL/FEA
$pkeyid = openssl_get_privatekey(file_get_contents($file));
openssl_sign($cadena_original, $crypttext, $pkeyid, OPENSSL_ALGO_SHA256);
#openssl_free_key($pkeyid);
if (PHP_VERSION_ID < 80000) {
    openssl_free_key($pkeyid);
} 

$sello = base64_encode($crypttext);      // lo codifica en formato base64
$root->setAttribute("Sello",$sello);
 
$file="/home/mhurtado/RegistrationTutorial/CreacionCFDI/CSD/certificado.pem";      // Ruta al archivo de Llave publica
$datos = file($file);
$certificado = ""; $carga=false;
for ($i=0; $i<sizeof($datos); $i++) {
    if (strstr($datos[$i],"END CERTIFICATE")) $carga=false;
    if ($carga) $certificado .= trim($datos[$i]);
    if (strstr($datos[$i],"BEGIN CERTIFICATE")) $carga=true;
}
// El certificado como base64 lo agrega al XML para simplificar la validacion
$root->setAttribute("Certificado",$certificado);
}
 
 
// {{{ Termina, graba en edidata o genera archivo en el disco
function satxmlsv40_termina($arr,$dir) {
global $xml, $conn;
$xml->formatOutput = true;
$todo = $xml->saveXML();
$nufa = $arr['Serie'].$arr['Folio'];    // Junta el numero de factura   serie + folio
$paso = $todo;
file_put_contents("/home/mhurtado/RegistrationTutorial/Cancelacion/No cancelable/InvoiceC.xml",$todo);
 
    $xml->formatOutput = true;
    $file=$dir.$nufa."InvoiceC.xml";
    $xml->save($file);

return($todo);
}

// {{{ Funcion que carga los atributos a la etiqueta XML
function satxmlsv40_cargaAtt(&$nodo, $attr) {
$quitar = array('Sello'=>1,'NoCertificado'=>1,'Certificado'=>1);
foreach ($attr as $key => $val) {
    $val = preg_replace('/\s\s+/', ' ', $val);   // Regla 5a y 5c
    $val = trim($val);                           // Regla 5b
    if (strlen($val)>0) {   // Regla 6
        $val = utf8_encode(str_replace("|","/",$val)); // Regla 1
        $nodo->setAttribute($key,$val);
    }
}
}

function xml_to_array($root) {
    $result = array();

    if ($root->hasAttributes()) {
        $attrs = $root->attributes;
        foreach ($attrs as $attr) {
            $result['@attributes'][$attr->name] = $attr->value;
        }
    }

    if ($root->hasChildNodes()) {
        $children = $root->childNodes;
        if ($children->length == 1) {
            $child = $children->item(0);
            if ($child->nodeType == XML_TEXT_NODE) {
                $result['_value'] = $child->nodeValue;
                return count($result) == 1
                    ? $result['_value']
                    : $result;
            }
        }
        $groups = array();
        foreach ($children as $child) {
            if (!isset($result[$child->nodeName])) {
                $result[$child->nodeName] = xml_to_array($child);
            } else {
                if (!isset($groups[$child->nodeName])) {
                    $result[$child->nodeName] = array($result[$child->nodeName]);
                    $groups[$child->nodeName] = 1;
                }
                $result[$child->nodeName][] = xml_to_array($child);
            }
        }
    }

    return $result;
}
 
?>