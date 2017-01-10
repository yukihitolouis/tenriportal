<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
				xmlns:html="http://www.w3.org/TR/REC-html40"
				xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
	<xsl:template match="/">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<style type="text/css">
					table {
						background: red;
					}
				</style>
			</head>
			<body>
				<table border="1">
					<tr bgcolor="#9acd32">
						<th>Title</th>
						<th>Content</th>
					</tr>

					<xsl:for-each select="channel:item">
						<tr bgcolor="#9acd32">
							<td><xsl:value-of select="title"/></td>
							<td><xsl:value-of select="content"/></td>
						</tr>
					</xsl:for-each>
				</table>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>