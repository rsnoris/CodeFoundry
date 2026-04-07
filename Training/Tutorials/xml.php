<?php
$tutorial_title = 'XML';
$tutorial_slug  = 'xml';
$quiz_slug      = 'xml';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>XML (eXtensible Markup Language) is a text-based format designed to store and transport structured data in a human-readable, machine-parseable way. Unlike HTML, XML has no predefined tags — you define your own vocabulary to describe any kind of data, from configuration files to book catalogues to web service messages.</p><p>This tier covers the rules of well-formed XML, the key building blocks (elements, attributes, text content), and the variety of real-world contexts where XML is still the dominant data format.</p>',
        'concepts' => [
            'XML declaration: <?xml version="1.0" encoding="UTF-8"?>',
            'Well-formed vs. valid XML: the two levels of correctness',
            'Elements: start tag, end tag, self-closing, nesting rules',
            'Attributes: name="value" syntax and when to prefer them over child elements',
            'Character data (CDATA) vs. parsed character data',
            'XML special characters and entity references: &amp;, &lt;, &gt;, &apos;, &quot;',
            'Comments <!-- --> and processing instructions <?target data?>',
        ],
        'code' => [
            'title'   => 'Well-formed XML document',
            'lang'    => 'xml',
            'content' =>
'<?xml version="1.0" encoding="UTF-8"?>
<!-- Book catalogue -->
<catalogue>
  <book isbn="978-0-13-110362-7" inStock="true">
    <title>The C Programming Language</title>
    <authors>
      <author>Brian W. Kernighan</author>
      <author>Dennis M. Ritchie</author>
    </authors>
    <year>1988</year>
    <price currency="USD">45.99</price>
  </book>
</catalogue>',
        ],
        'tips' => [
            'Every XML document must have exactly one root element — all other elements must be inside it.',
            'Use meaningful, lowercase-with-hyphens tag names that describe the data, not how it is displayed.',
            'Open your XML file in a browser to get immediate, visual feedback on whether it is well-formed.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Well-formed XML obeys syntax rules, but <em>valid</em> XML also conforms to a declared grammar. DTD (Document Type Definition) was the original validation mechanism; XML Schema (XSD) is the modern, more powerful successor that supports typed elements, default values, and complex structural constraints.</p><p>XML Namespaces solve the naming-collision problem that arises when combining XML vocabularies from different sources — for example, mixing XHTML and SVG in the same document.</p>',
        'concepts' => [
            'Document Type Definition (DTD): inline and external, ELEMENT and ATTLIST declarations',
            'DTD content models: #PCDATA, EMPTY, ANY, sequences, choices',
            'XML Schema (XSD): xs:element, xs:attribute, xs:complexType, xs:sequence',
            'XSD built-in data types: xs:string, xs:integer, xs:date, xs:boolean',
            'XSD restrictions: minOccurs, maxOccurs, minLength, pattern',
            'XML Namespaces: URI identifiers, xmlns prefix, default namespace',
            'Namespace-qualified elements and attributes',
        ],
        'code' => [
            'title'   => 'XML Schema (XSD) example',
            'lang'    => 'xml',
            'content' =>
'<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">

  <xs:element name="catalogue">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="book" type="BookType" maxOccurs="unbounded"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>

  <xs:complexType name="BookType">
    <xs:sequence>
      <xs:element name="title"  type="xs:string"/>
      <xs:element name="year"   type="xs:gYear"/>
      <xs:element name="price"  type="xs:decimal"/>
    </xs:sequence>
    <xs:attribute name="isbn"    type="xs:string"  use="required"/>
    <xs:attribute name="inStock" type="xs:boolean" default="false"/>
  </xs:complexType>

</xs:schema>',
        ],
        'tips' => [
            'Prefer XSD over DTD for new projects — it is far more expressive and type-safe.',
            'Use xs:pattern with regular expressions to validate strings like ISBNs, postal codes, and dates.',
            'Always declare namespaces in the root element when combining XML vocabularies.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>XPath is the query language for navigating XML document trees. It uses a path-like syntax to select nodes, attributes, and text content, and forms the foundation for both XSLT transformations and DOM-based XML processing in every major programming language.</p><p>XSLT (eXtensible Stylesheet Language Transformations) turns one XML document into another format — HTML, plain text, CSV, or a different XML vocabulary — using declarative template rules, making it the cornerstone of XML-based reporting and data-exchange pipelines.</p>',
        'concepts' => [
            'XPath axes: child, parent, ancestor, descendant, following-sibling',
            'XPath predicates: [position()], [@attribute], [text()="value"]',
            'XPath functions: string(), number(), count(), contains(), normalize-space()',
            'XSLT stylesheet structure: xsl:stylesheet, xsl:template, xsl:apply-templates',
            'XSLT control flow: xsl:if, xsl:choose, xsl:when, xsl:otherwise',
            'XSLT iteration: xsl:for-each and xsl:sort',
            'Generating HTML output from XML with XSLT',
            'Calling XSLT from JavaScript (XSLTProcessor) and server-side (Saxon, Xalan)',
        ],
        'code' => [
            'title'   => 'XSLT template transforming XML to HTML',
            'lang'    => 'xml',
            'content' =>
'<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="html" indent="yes"/>

  <xsl:template match="/">
    <html>
      <body>
        <h1>Book Catalogue</h1>
        <table border="1">
          <tr><th>Title</th><th>Year</th><th>Price</th></tr>
          <xsl:for-each select="catalogue/book">
            <xsl:sort select="year" order="descending"/>
            <tr>
              <td><xsl:value-of select="title"/></td>
              <td><xsl:value-of select="year"/></td>
              <td><xsl:value-of select="price"/></td>
            </tr>
          </xsl:for-each>
        </table>
      </body>
    </html>
  </xsl:template>

</xsl:stylesheet>',
        ],
        'tips' => [
            'Learn XPath expressions in the browser console with $x("//path/expr") — instant feedback.',
            'Use xsl:apply-templates instead of xsl:for-each for cleaner, more modular XSLT.',
            'Saxon is the reference XSLT 3.0 processor — use it for complex transformations outside the browser.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>SOAP (Simple Object Access Protocol) web services rely on XML envelopes for request/response messaging. Although REST/JSON has displaced SOAP in most new projects, a significant share of enterprise systems — banking, healthcare, government — still use WSDL-defined SOAP APIs that developers must consume and maintain.</p><p>XML processing in code requires choosing between SAX (event-driven, memory-efficient) and DOM (tree-in-memory, easier to navigate) parsers. This tier also covers streaming with StAX/XmlReader, XLink for hyperlinks between XML documents, and XML canonicalization needed for digital signatures.</p>',
        'concepts' => [
            'SOAP structure: Envelope, Header, Body, Fault elements',
            'WSDL (Web Services Description Language): types, messages, portType, binding',
            'DOM vs. SAX vs. StAX/Pull parsers: when to choose each',
            'DOM Level 2 Core API: getElementById, createElement, appendChild, querySelector',
            'XLink: simple and extended links across XML documents',
            'XML canonicalization (C14N) and exclusive canonicalization',
            'XML Digital Signatures (XMLDSig): SignedInfo, SignatureValue, KeyInfo',
            'XML Encryption (XMLEnc): encrypting elements and key transport',
        ],
        'code' => [
            'title'   => 'Parsing XML with DOM in Python',
            'lang'    => 'python',
            'content' =>
"import xml.etree.ElementTree as ET

xml_data = '''<?xml version=\"1.0\"?>
<catalogue>
  <book isbn=\"978-0-13-110362-7\">
    <title>The C Programming Language</title>
    <year>1988</year>
    <price currency=\"USD\">45.99</price>
  </book>
</catalogue>'''

root = ET.fromstring(xml_data)

for book in root.findall('book'):
    isbn  = book.get('isbn')
    title = book.findtext('title')
    price = book.findtext('price')
    curr  = book.find('price').get('currency')
    print(f'{isbn}: {title} — {curr} {price}')",
        ],
        'tips' => [
            'Use SAX or StAX for large XML files (> 10 MB) to avoid loading the entire document into memory.',
            'Understand SOAP Fault structure before debugging SOAP services — error details live inside Fault.',
            'Validate SOAP messages against the WSDL schema before sending them to production endpoints.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert XML encompasses XQuery — the SQL of XML, capable of cross-document joins and complex transformations — and XSLT 3.0 additions including streaming mode, maps/arrays, and higher-order functions that bring functional programming patterns to XML transformation pipelines.</p><p>XML in modern architectures appears as a first-class citizen in databases (PostgreSQL xml type, SQL Server\'s FOR XML), configuration formats (Maven POM, Spring beans, Android manifests), and protocol layers (Atom/RSS feeds, SVG, OOXML). Mastering schema evolution and backward-compatible XML API design makes you a reliable integrator in enterprise and open-standards ecosystems.</p>',
        'concepts' => [
            'XQuery: FLWOR expressions (For, Let, Where, Order by, Return)',
            'XQuery functions, modules, and type system',
            'XSLT 3.0: streaming mode with xsl:stream and xsl:iterate',
            'XSLT 3.0: maps, arrays, and higher-order functions (fn:apply)',
            'XML in SQL databases: XMLQUERY, XMLTABLE, FOR XML, OPENXML',
            'Atom and RSS feed structure and publishing conventions',
            'OOXML (Office Open XML): Excel .xlsx, Word .docx internal structure',
            'Schema evolution: versioning strategies and forward/backward compatibility',
        ],
        'code' => [
            'title'   => 'XQuery FLWOR expression',
            'lang'    => 'xml',
            'content' =>
"(: XQuery against an XML database :)
xquery version '3.1';

let \$catalogue := doc('books.xml')/catalogue
for \$book in \$catalogue/book
where xs:decimal(\$book/price) > 30
  and \$book/@inStock = 'true'
order by \$book/title ascending
return
  <result>
    <title>{ \$book/title/text() }</title>
    <price>{ \$book/price/text() }</price>
  </result>",
        ],
        'tips' => [
            'Use BaseX or eXist-db as native XML databases when XQuery performance matters.',
            'Study the XSLT 3.0 spec streaming guidelines — large document processing requires careful template design.',
            'Follow the W3C XML Working Group\'s GitHub repos for upcoming specification changes.',
            'Automate schema validation in CI/CD pipelines to catch breaking XML format changes before deployment.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
