import json
from subprocess import call
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
f1 = open('output.txt','w')
for key in dict:
	
	key1 = key.encode(encoding='UTF-8')
	if "Mobiles & Acc" in key:
		print len(dict[key])
		for i in range (0,len(dict[key])):
			call(["php", "Oauth.php",dict[key][i]])
		#call(["date"])
	dict[key][i] = dict[key][i].encode(encoding='UTF-8')
	try:
		products = ",".join(dict[key])
		#print products
		#f1.write(key1+"-->"+products+'\n')
	except Exception as e:
		print key
		continue
