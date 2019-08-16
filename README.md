# thesis_presentation
Scripts to control my thesis presentation, used on [524.se](http://524.se).

## frames.txt
Contains the frame numbers (between first and second **Start...**) where the video should pause "in between slides".
## repeat.txt
Contains the indices (based on *frames.txt*) where the video shouldn't stop but should repeat from its previous pause position.
*Example*: If `frames[16] == 168` and `frames[17] == 172`, and *repeat.txt* includes `17`,  the video would pause at frame 168, then resume (upon user interaction), then play frames `169, 170, 171, 172, 169, 170, 171, 172, 169, 170, 171, 172, 169, 170, ...` in a repeating loop until the user interacts.
<!--stackedit_data:
eyJoaXN0b3J5IjpbNjQ2Mzg5MDUzLDEzMDE3Mzk5MDVdfQ==
-->