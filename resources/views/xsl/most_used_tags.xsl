<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html>
            <body>
                <div class="container">
                    <h2 class="mb-4">Most Used Tags Report</h2>
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Tag</th>
                                <th>Usage Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <xsl:for-each select="tags/tag">
                                <tr>
                                    <td><xsl:value-of select="name"/></td>
                                    <td><xsl:value-of select="count"/></td>
                                </tr>
                            </xsl:for-each>
                        </tbody>
                    </table>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
