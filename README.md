# WEBB19 Slutprojekt

## Instruktioner
Ni ska bygga en wordpress plugin som sammanflätar alla koncept genom kursen.
Det finns två alternativ vid val av plugin att utveckla, 
antingen utvecklar ni enligt exempelet nedan, eller så väljer ni att fullfölja en egen idé.

En i gruppen forkar detta repo, och lägger till den andra som collaborator.
Klona sedan ner det till er plugin-mapp.

## G-Krav
* Filters och Hooks
* Custom Post Type
* Custom Table
* En N->M-relation
* En tillhörande widget

## VG-krav
* En admin page för er plugin [https://codex.wordpress.org/Adding_Administration_Menus](https://codex.wordpress.org/Adding_Administration_Menus)

## Exempelplugin

### Ordersystem
Utveckla vidare på er CPT Product och lägg till funktionalitet att användaren kan lägga produkter i en varukorg. 
Varukorgen kan sedan skickas för att skapa en Order (hoppa över steget med betallösningar, se det som en beställning).
Varje order ska ha 

* en tidsstämpel när den genomfördes
* vilken användare som genomförde ordern.
* vilken _status_ ordern har (recieved, canceled, shipped, delivered)

Adminsidan för ordersystemet ska lista de 10 senaste ordrarna och admin ska kunna ändra order status.

### Pluginidé
Custom post types med olika spel som användare kan ratea. 
Widgeten ska visa de spel som är högst rankade. Man kan välja upp till 10 spel. 
Adminsidan innehåller val för att ändra rating symboler som skrivs ut samt färgen på rate/unrate knappar.