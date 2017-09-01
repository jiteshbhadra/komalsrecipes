Installation steps:

You can installed MRM Recipes Plugin either from wordpress admin panel or do it manually by following below mentioned steps:

1. Unzip the archive and put the 'mrm_recipes' folder into your plugins folder (/wp-content/plugins/).
2. Activate the plugin from the Plugins menu.

MRM Plugin Usage:-

1) Once you have activated MRM plugin the admin need to configured the MRM username and magic button position from the setting page. 
You can access Plugin setting page by follow below path:
  
 WP-Admin -> MRM Settings -> MRM setting configurations.

Note: MRM username is must for MRM Plugin. Plugin will not work without filling MRM Username.

2) After completed configuration part of MRM settings, open posts section using which  you can mange “posts” as a recipe.

3) For Creating xml Admin need to click on any post (Recipe) admin tab which will open edit screen which contain magic button "Add This Recipe to MRM"  checkbox.

4) For filtration We used classes as a keyword from recipe content. So you need to use classes  along with content in your wordpress blog post. Classes name(Keywords) which we used for filtration of  different  section are as follows:
 
a) For ingredients you need to use "ingredients" class.
b) For Instruction you need to use "instructions" class.
c) for summary section you need to use "summary" class
d) for prepation section you need to use (itemprop="prepTime") 
e) for total time section you need to use (itemprop="totalTime")
f) for serving and serves section you need to use "yield" class

5) once you have added the class related to content click on update or publish, then xml will generate on cloud server. Currently we have uploaded xml file on our server. 

6) Once the xml generation process will complete plugin will insert xml data into MRM database. 