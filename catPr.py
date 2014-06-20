import json
from subprocess import call
import pdb
f = open("feedfinal.csv")
array = []
dict = {}
i = 0
for line in f:
	array = json.loads(line)
	#print array['_cn']

	if array['_cn'] in dict:
		dict[array['_cn']].append(array['_pn'])
	else:
		dict[array['_cn']] = []
		dict[array['_cn']].append(array['_pn'])

	# array = line.split("\t")
	# if len(array)>=5:
	# 	#print array,len(array)
	# 	if array[5] in dict:
	# 		dict[array[5]].append(array[1])
	# 	else:
	# 		dict[array[5]] = []
	# 		dict[array[5]].append(array[1])
for key in dict:
	
	key1 = key.encode(encoding='UTF-8')
	if "Mobiles & Acc" in key:
		print len(dict[key])

		#called Oauth php. Stores bunch of tweet info about product in 1234.txt
		for i in range (0,len(dict[key])):
			try:
				#pdb.set_trace()
				text = ""
				array = []
				truncatedPName = []
				pName = ""
				truncatedPName = dict[key][i].split(' ')
				if(len(truncatedPName)>=2):
					for i in range(0,2):
						pName = pName + " " + truncatedPName[i]
				else:
					pName = dict[key][i]
				pName = pName.lstrip()
				call(["php", "Oauth.php",pName])
				filename =  pName.encode(encoding = 'base64')
				f2 = open(filename[:len(filename)-1]+"_tweet")
				f1 = open(filename[:len(filename)-1],'w')
				
				#parsing through 1234.txt. Getting tweet uhfo and stroing into output.txty
				for line in f2:
					array = json.loads(line)
					foo = ""
					for tweet in array['statuses']:
						#print tweet['text']
						# for noise_word in noise_words_set:
						# 	if " "+noise_word+" " in text:
						# 		text.replace(" "+noise_word+" ","")

						text = tweet['text'].encode(encoding = 'UTF-8')
						f1.write(text)

					#calling tweetsentiment parses and giving output.txt as filename
					
					call(["php", "tweetSentimentParserAndCount.php",pName,filename[:len(filename)-1]])
			except Exception as e:
				print e
				continue

		
		#call(["date"])
	dict[key][i] = dict[key][i].encode(encoding='UTF-8')
	try:
		products = ",".join(dict[key])
		#print products
		#f1.write(key1+"-->"+products+'\n')
	except Exception as e:
		print key
		continue
