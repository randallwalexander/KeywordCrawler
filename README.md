# KeywordCrawler

A simple tool used for Keyword Research.

KeywordCrawler is a small PHP script that I developed and is used to extract the top 20 keywords, counts the number of times each keyword has been used on the page then presents a longer list of keywords in a comma separated list helping you build the most relevant keywords list and meta tag. KeywordCrawler also allows you to see the most words used on a webpage including the stop words. 

The ability to quickly look into these two elements of your competition’s pages will allow you to write a page with the same keywords at a higher density and fewer stop words which will give you a much more powerful page. This will work on product pages, articles, reviews, news releases, and any other page that relies on keyword rich content. 

index.php - Main landing page and script. Crawls a given website - keywords found on the web page in freq 
with stop words stripped.

metajunk.php - Crawls a given website - keywords found on the web page in freq with stop words.

stopwords.txt - List of stopwords used by index.php - Feel free to add and remove your own stop words to this file as needed. Each website may require custom stop words. 
