import json
f = open("test.txt")
f1 = open("parsedTweets.txt","w")

text = ""
noise_words_set = {'of', 'the', 'at', 'for', 'in'}

for line in f:
	array = json.loads(line)
	foo = ""
	for tweet in array['statuses']:
		#print tweet['text']
		for noise_word in noise_words_set:
			if " "+noise_word+" " in text:
				text.replace(" "+noise_word+" ","")

		text = tweet['text'].encode(encoding = 'UTF-8')
		f1.write(text+" "+str(tweet['retweet_count'])+" "+str(tweet['favorite_count']))
