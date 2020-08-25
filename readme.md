CSV Importer

All commands run via index.php

CLI commands:

Actions:

    import - used for file import to db 
    search - used for search in db
    truncate - truncate table
    generate - generate dummy data
    createtable - create table users

Additional parameters for actions:

    import:
        required:    
            --path: full path to import file 
            --type: file type                  
        example:
           php index.php --action=import --type=csv --path=/home/user/data.csv      
                
    search:
        optional:
            --name: search by exact name
            --email: search by exact email
            --forceCacheUpdate: force memcached to update data, default = false
            --limit: results limit, default = 10
        
    truncate:
        example:
            php index.php --action=truncate
            
    generate:     
        required:
            --type: file type
        optional:    
            --count: how many users generate, default 10
        example:
            php index.php --action=generate --type=csv --count=1000    
    createtable:
        example:
            php index.php --action=createtable        
               