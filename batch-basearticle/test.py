# print("hello")

# nb=3
# nb=float("{:.2f}".format(nb))
# nb=round(nb, 2)
# print(f"nb{nb}")



data="1,12"
if not isinstance(data, float):
	data=data.replace(",", ".")
	data=float(data)
	marge=float("{:.2f}".format(data))
	print ("converti en float")
else:
	print(type(data))
	print ("c'est deja un float")

	# marge=float("{:.2f}".format(data))