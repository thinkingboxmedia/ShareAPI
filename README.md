# ShareAPI
---
A plug and play social media sharing and data retrieval API

## Requirements
---
- PHP 5.5.*
- Composer (PHP Package manager)

## Installation
---
```sd
php composer.phar install
```
Drag whole directory into any accessible directory, and voila, you're done!

## Setup
---
When you are done installation, open up config.php. There are a few options you will need to fill out here for things to work smoothly. Below is a detailed description of each option.

- **domain**: The fully qualified domain name of where the API exists (index.php). Please leave out any trailing /'s
- **{platform}_enabled**: If set to false, requests to that section of the API will be disallowed
- **enable_profanity_filter**: If true, and profanity will be replaced by ***'s
- **cross_domain**: Is this API going to live on a different domain than the client? 
- **requesting_domain**: If cross_domain is true, what is the requesting domain going to be. Please put the EXACT FQD.

The rest of the parameters are key's for certain social media platforms. To get these keys, you need to create an app on each platform. Requests to certain platforms will be dissallowed if these are left empty.

## Usage - OAuth Flow
---
The main feature of this API is the easy to use OAuth user flow. Simply redirect to a domain, and you're done! 
***In these examples, {platform} can be either 'facebook', 'twitter', 'linkedin', or 'tumblr'.***

To generate the login URL, simply make a GET request to **/{platform}/login**

The response will look like:
```JSON
{
    "status" : "success" ,
    "response" : "SOME URL" ,
}
```

Take the response and open it in a new window
```Javascript
window.open(url);
```

When the user is done with their actions, **the window will automatically close.** You are now done! **Everything you need is stored in a server side session, so you don't have to worry about anything.**

## Usage - Sharing and Data Retrieval
---
All requests will return either:
```JSON
{
    "status" : "success" ,
    "response" : "some success message OR some JSON object" ,
}
```

OR

```JSON
{
    "status" : "error",
    "response" : "some error message" ,
}
```
### **Facebook**

#### POST /facebook/post/status 
Post a text status .  
**Params:**   
message (string) - **required** - The text to post to the user's timeline

#### POST /facebook/post/link
Post a link to the user's timeline.  
**Params:**   
link (string) - **required** - Link to website you wish to share       
message (string) - Message to go along with the link

#### POST /facebook/post/photo
Post a photo to the user's timeline.  
**Params:**   
source (string) - **required** - Path to image you wish to share (external or server)   
message (string) - Message to go along with the image

#### POST /facebook/post/video
Post a video to the user's timeline.  
**Params:**   
source (string) - **required** - Path to video you wish to share (external or server)   
title (string) - Title of video   
description (string) - Description of video

#### GET /facebook/user
Post a video to the user's timeline. Please note that you must have certain permissions for some values.   
**Params:**   
fields (string) - **required** - Comma seperated list of values you want returned. Possible values can be viewed [here](https://developers.facebook.com/docs/graph-api/reference/user/). 



### **Twitter**
#### POST /twitter/post/tweet
Post a tweet to the user's feed.  
**Params:**   
message (string) - **required** - Text to include in the tweet  

#### POST /twitter/post/image
Post an image to the user's feed.         
**Params:**   
source (string) - **required** - Path to image to share (external or server)      
message (string) - Text to go with the image

#### GET /twitter/search
Search twitter for latest tweets matching certain criteria   
**Params:**   
search (string) - **required** - A twitter query to search with. View the docs [here](https://dev.twitter.com/rest/public/search)    
type (string) - What type of results do you want. (Can be either **popular**, **recent**, **or mixed**)   
count (int) - How many results to return   
geo (geocode) - Search for tweets posted in a certain radiius. View the docs [here](https://dev.twitter.com/rest/reference/get/search/tweets)   
since (int - tweetID) - Only return tweets after a certain tweet ID

### **Tumblr**
#### GET /tumblr/user/blogs
Returns a list of the current user's blogs. Needed to get the blogName param for all other requests.

#### POST /tumblr/post/text
Make a text post to the user's selected blog   
**Params:**   
message (string) - **required** - Text to include in the blog post   
blogName (string) - **required** - Name of blog to post to

#### POST /tumblr/post/photo
Make a photo post to the user's selected blog   
**Params:**   
source (string) - **required** - Source of image to share (external or server)      
blogName (string) - **required** - Name of blog to post to   
link (string) - Link to take user's to when the image is clicked    
caption (string) - Caption of photo

#### POST /tumblr/post/link
Make a link post to the user's selected blog   
**Params:**   
url (string) - **required** - URL of website to share     
blogName (string) - **required** - Name of blog to post to   
description (string) - Description of link         
thumbnail (string) - Source of photo to use as thumbnail        
author (string) - Author of website

### **LinkedIn**
#### GET /linkedin/user/info
Get any requested info from the user's profile   
**Params:**   
fields (string) - **required** - Comma seperated list of fields you want returned. Click [here](https://developer.linkedin.com/docs/fields/basic-profile) for possible values.

#### POST /linkedin/post
Share something to the user's page
**Params:**   
title (string) - Title of the post      
description (string) - Description of the post      
url (string) - URL of the page or website you wish to share      
source (string) - External source of the image you wish to share       
comment (string) - Comment to go along with the post      
visiblity (string) - Who can view this post (possible values: anyone (default), connections-only)


## Usage - Intents
---
If you do not wish to do the full OAuth flow, you may use these intents to allow the user to manually share some preselected items. Tumblr does not currently have a intent system, so full OAuth flow is required. All requests will return an object like this:

```JSON
{
    "status" : "success" ,
    "response" : "some url"
}
```

Just open the url in a new window, and you are done!

```javascript
window.open(url, 500, 500);
```

### POST /facebook/intent/share
Generate a facebook intent dialog. Please keep in mind that you cannot have pre-populated text using this method.   
**params**   
link (string) - Link of website to share   
picture (string) - Source of image to share (external or server) (GIFS MUST BE EXTERNAL)       
name (string) - Name of what you are sharing       
caption (string) - Caption to include in the post       
description (string) - Description of what you are sharing      
redirect_uri (string) - When the user is done sharing, take them to this link 

### POST /twitter/intent/tweet
Generate a tweet that the user can choose to tweet  
**params**   
message (string) - **required** - Tweet text     
url (string) - Link to website to share         
hashtags (string) - Comma seperate list of hashtags to include. (exclude hashtags)            
via (string) - Adds "shared via @VIA" to the end of the tweet     

### POST /twitter/intent/retweet
Generate a dialog to retweet a certain tweet   
**params**        
tweet_id (string) - **required** - ID of tweet to retweet.  

### POST /linkedin/intent/
Generate a dialog to share to LinkedIn   
**params**        
url (string) - **required** - URL of website you wish to share   
title (string) - Title of what you are sharing        
summary (string) - Short summary of the page you are sharing         
source (string) - Where did this website come from 



























