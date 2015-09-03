# Copy'n Paste Web App
Share text, URLs, etc. between your devices or within groups with two clicks. 

I coded this, cause I always need to share URLs, test-input, IDs etc. between my Linux development PC and the iPad or Android Phone -- without chatting and skyping with myself. 

# Set Up
1. Clone the GIT source to local files (git clone <GIThub-URL> 
2. Edit the `web/db_connection.php` script to point to your database.
3. Copy files from the `web` folder to your hosting provider, e.g. to folder `/copypaste`.
1. Create a database calling the `https://myhost.com/copypaste/create_tables.php`

# Usage
It's easy, just open the URL base folder, e.g. `https://myhost.com/copypaste/`

You can create a new copy-paste page. 

To load an exiting one, just enter the (5 digit) key and click the link. 

On the copy-paste page you can past new text using the form or copy text you pasted already.

# TODO
Add GUI functions to index.html 

# License
MIT License (MIT), Copyright (c) 2015 ma-ha