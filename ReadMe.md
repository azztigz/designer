# All About azztigz's Work

## Guidelines in creating SVG Template

* "transform matrix" should not be used in attributes. You can use "transform translate" instead or simply use x,y,height,width attributes.
* images from fotolia and text (<text>) tags should not be wrapped into group tags (<g>). 
*  when the text contains several rows (as it is in the templates with a turtle and a clock that I use in the project to show you) it should be built using <text> tags, but not <tspan>. 
* "transform matrix" should not be used in attributes. You can use "transform translate" instead or simply use x,y,height,width attributes.
* images from fotolia and text (<text>) tags should not be wrapped into group tags (<g>). 
*  when the text contains several rows (as it is in the templates with a turtle and a clock that I use in the project to show you) it should be built using <text> tags, but not <tspan>. 
* width and height attributes should be set using only numbers. ( width="100", but NOT width="100px")


## Some useful information on editing pictures

* SVG-pictures consists of elements which usually are grouped. And there can be groups inside groups and so on for some levels.
* When you load the element to the svg-editor, you can move the whole picture, change its size etc.
* When you double click on it you choose a group  which you clicked on. And now you are able to move and somehow change the whole group.
> For example it can be a picture of tree and a land. firstly you move them together. Then when you doubleclick again(see the tree template) you can move and change only a tree without a land. But tree itself is also a group. And if you  doubleclick one more time on this tree, you can edit it separatly from leaves and flowers on it. and the same way edit those leaves and flowers. So every time we go down to the deeper level of the elements of this svg.

    So knowing and understanding this principle, you can explain to designers how they have to create the svg-pics, so they were editable the way you want.

* And as for the text. If text is inside of any group, it won't be editable. That's your problem. I found it in most svg templates you sent me. 
