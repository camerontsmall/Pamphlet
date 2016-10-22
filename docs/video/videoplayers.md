#Video Player Options

##Each video player in Pamphlet takes data in a particular format in order to generate a video.
##You may also be able to add parameters to modify the player experience

For players using a remote API (eg. Vimeo), some additional data may be pulled from the remote servers
for the generated video. However, to allow efficient searching, the following fields are mandatory:

* Title
* Tags 
* Poster
* Date

Options, as described in the individual player specs below, are added in the Custom Parameters section of the editor.
Type the name in the name section and the value in the value section.

##VideoJS

Enter any number of video file sources which are supported by VideoJS in the Sources section.
When entering multiple sources be sure to enter the vertical resolution of the source in the Size field.

Options:

####Radio station current song info
<code>server-type</code>
Enter either <code>shoutcast</code> or <code>icecast</code>

<code>nowplaying-url</code>
Enter the URL of the server's info page
(for Icecast this will usually be http://[server_name]:[port]/status-json.xsl)

####Animated backgrounds
<code>background-type</code>
Either <code>gif</code> or <code>gifv</code>

<code>animated-background-url</code>
URL of the animated background file. When using animated backgrounds, the poster
value should still be filled in with a static image, and this will be used
for thumbnails.

####Autoplay
