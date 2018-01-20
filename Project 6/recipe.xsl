<!-- Gautam Ramachandruni -->
<?xml version = "1.0" encoding = "ISO-8859-1"?>
<xsl:stylesheet version = "1.0" xmlns:xsl = "http://www.w3.org/1999/XSL/Transform">
<xsl:output method = "html"/>
<xsl:template match = "/collection">
<html> 
<body style = "font-family:Times New Roman;font-size:12pt;background-color:#EEEEEE">
    <h1><xsl:value-of select = "description"/></h1>
    <xsl:for-each select = "recipe">
        <h2><xsl:value-of select = "title"/></h2>
        Recipe: <xsl:value-of select = "./@id"/><br/>
        <xsl:value-of select = "date"/><br/>
        <h3>Ingredients</h3>
        <ul>
        <xsl:for-each select = "ingredient">
            <li><xsl:value-of select = "./@name"/></li>
            amount: <xsl:value-of select = "./@amount"/>&#160;
            <xsl:value-of select = "./@unit"/><br/>
        </xsl:for-each>
        </ul>
        <h3>Preparation</h3>
        <ol>
        <xsl:for-each select = "preparation/step">
            <li><xsl:value-of select = "."/></li>
        </xsl:for-each>
        </ol>
        <br/>Comment: <xsl:value-of select = "comment"/><br/>
        <h3>Nutrition Facts</h3>
        <xsl:for-each select = "nutrition">
            Calories: <xsl:value-of select = "./@calories"/><br/>
            Fat: <xsl:value-of select = "./@fat"/><br/>
            Carbs: <xsl:value-of select = "./@carbohydrates"/><br/>
            Protein: <xsl:value-of select = "./@protein"/><br/>
        </xsl:for-each>
        </xsl:for-each>
</body>
</html>
</xsl:template>
</xsl:stylesheet>