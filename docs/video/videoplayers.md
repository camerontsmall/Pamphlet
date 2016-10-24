#Video Player Options

Each video player in Pamphlet takes data in a particular format in order to generate a video.
You may also be able to add parameters to modify the player experience.

For players using a remote API (eg. Vimeo), some additional data may be pulled from the remote servers
for the generated video. However, to allow efficient searching, the following fields are mandatory:

* Title
* Tags 
* Poster
* Date

Poster images should be PNG or JPEG, 16:9 aspect ratio, at least 1280x720 pixels in size. 1920x1080 JPEGs recommended.

Options, as described in the individual player specs below, are added in the Custom Parameters section of the editor.
Type the name in the name section and the value in the value section.

##IFrame

Enter a single webpage URL to use as a source.

##VideoJS

Enter any number of video file sources which are supported by VideoJS in the Sources section. 
It is important to enter the correct MIME type for each file added. 
When entering multiple sources be sure to enter the vertical resolution of the source in the Size field.

Options:

####Player styling

* <code>theme_color</code>

Enter a valid CSS colour to change the player bar background colour from the default

* <code>ident</code>

Enter a path to a transparent PNG file to use as an ident. This will be displayed in the top-left corner.

##Audio

Enter one audio file as a source

####Player styling

* <code>theme_color</code>

Enter a valid CSS colour to change the player bar background colour from the default

* <code>ident</code>

Enter a path to a transparent PNG file to use as an ident. This will be displayed in the top-left corner.


####Radio station current song info

If using to generate a player for an online radio stream, entering the following options will allow the generated video to be populated with the station's current song info.

* <code>server_type</code>

Enter either <code>shoutcast</code> or <code>icecast</code>

* <code>nowplaying_url</code>

Enter the URL of the server's info page
(for Icecast this will usually be <code>http://[server_name]:[port]/status-json.xsl</code>)

####Animated backgrounds

For audio-only VideoJS objects, external implementations may support animated backgrounds. These parameters are not currently supported natively.

* <code>animated_background_type</code>

Either <code>gif</code> or <code>gifv</code>

* <code>animated_background_url</code>

URL of the animated background file. When using animated backgrounds, the poster
value should still be filled in with a static image, and this will be used
for thumbnails. When using gifv backgrounds, make sure to replace the file extension in the URL with "webm".

##YouTube

Enter a single YouTube video ID as a source. This can be found in the URL for the video, for example:

URL: <code>https://www.youtube.com/watch?v=G7DDVVN648A</code><br />
Code : <code>G7DDVVN648A</code>

For generated videos, the poster image will be fetched from the YouTube servers. You must still provide a poster image for use as a thumbnail.

##Vimeo

Enter a single Vimeo video ID as a source. This can be found in the URL for the video, for example:

URL: <code>https://vimeo.com/186269874</code><br />
Code : <code>186269874</code>

Options:

* <code>no_api</code>

Set to <code>true</code> to prevent API overwriting values.

##SoundCloud

Enter a single Soundcloud item ID as a source. This is a little harder to find than the other sites.

1. Browse to the track you'd like to embed
2. Click Share > Embed
3. Tick "WordPress Code"
4. This will give you a code like the following:
  <code>[soundcloud url="https://api.soundcloud.com/tracks/284314959" params="auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&visual=true" width="100%" height="450" iframe="true" /]</code>

The code is the last part of the URL field, in this example it is <code>284314959</code>


