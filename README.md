# thesis_presentation
Scripts to control my thesis presentation, used on [524.se](http://524.se).

## frames.txt
Contains the frame numbers (between first and second **Start...**) where the video should pause "in between slides".
## repeat.txt
Contains the indices (based on *frames.txt*) where the video shouldn't stop but should repeat from its previous pause position.
*Example*: If `frames[16] == 168` and `frames[17] == 259`, and *repeat.txt* includes `17`,  the video would pause at frame 168, then resume (
<!--stackedit_data:
eyJoaXN0b3J5IjpbNjY4MDcwOTUzLDEzMDE3Mzk5MDVdfQ==
-->