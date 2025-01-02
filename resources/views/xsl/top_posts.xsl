<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html>
            <head>
            </head>
            <body>
                <div class="container">
                    <h2 class="mb-4">Top Posts Report</h2>
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Title</th>
                                <th>Likes</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            <xsl:for-each select="posts/post">
                                <tr>
                                    <td><xsl:value-of select="title"/></td>
                                    <td><xsl:value-of select="likes"/></td>
                                    <td><xsl:value-of select="comments"/></td>
                                </tr>
                            </xsl:for-each>
                        </tbody>
                    </table>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
