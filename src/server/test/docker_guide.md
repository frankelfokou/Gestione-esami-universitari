## Blocks

http block -> global block
server block -> blocks appended to the global

## Directives

- **listen**: *Specifies the port (usually 80 for HTTP or 443 for HTTPS).*
- **server_name**: *Defines the domain or IP that this server block will handle \[URL\].*
- **root**: *Specifies the location of the files to be served, like your HTML files.*
- **location**: *Defines the string used to check against the requested uri, if matched, provides the instructions for the specified request.*