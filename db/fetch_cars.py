# Importing basic libraries
import os, requests, re, bs4, mysql.connector
# Import urllib for downloads
from urllib import request, parse
# Pillow used only to change png to jpg
from PIL import Image

# Should use the database?
use_db = True

# Basic constants
base_url = "https://nfsworld.fandom.com"
args_url = "/wiki/Cars"
# 3 brands with a composed name
composed_brands = ["Alfa Romeo", "Aston Martin", "Ford Shelby"]

# Used for stats
all_colors = []
all_cars = []
all_brands = []

# Create directories for downloads
os.makedirs("img", exist_ok=True)
os.makedirs("img/cars", exist_ok=True)
os.makedirs("img/constructors", exist_ok=True)

# Get main page
request_data = requests.get(base_url + args_url)
html_page = bs4.BeautifulSoup(request_data.content, "html.parser")
page_content = html_page.find("div", class_="mw-content-ltr")
# Get all cars on the main page
car_list = page_content.find_all("li")
# car_list = [car_list[125]] # Testing only the selected car

if use_db:
    # Connect to local !info2_cars must be created before!
    db = mysql.connector.connect(host="localhost", user="root", password="", database="info2_cars")
    cursor = db.cursor()

# In case some infos missing, shift the index
shift = 0
# Car id counter (in case AI doesn't work)
car_id = 0
# Fetch from the main page direcly !abandoned!
"""
for car in car_list:
    car_id += 1

    car_titles = car.find_all("a", title=True)
    all_cars.append(car_titles[-1]["title"])
    car_name = car_titles[-1]["title"]
    if any([x for x in composed_brands if x in car_name]):
        car_brand = car_name.split()[0]
    else:
        car_brand = car_name.split()[0]
    del car_titles[-1]

    for color in car_titles:
        all_colors.append(color["title"])
        car_color = color["title"]
        print(car_color)

    print(car_id, car_name, car_brand)
"""

# Fetch from every car link
for car in car_list:
    href_car = car.find_all("a", href=True)[-1]["href"]

    # Car request
    request_car = requests.get(base_url + href_car)
    html_car_page = bs4.BeautifulSoup(request_car.content, "html.parser")

    # Car id counter for index (or if AI doesn't work)
    car_id += 1
    car_name = html_car_page.find("h1", class_="page-header__title").text.replace("/", "-")
    car_base_name = car_name.replace(" ", "_")
    print("Processing " + car_name)
    all_cars.append(car_name)
    # Specific car with no information
    if car_name == "Speed Rabbit SUV":
        car_id -= 1
        continue
    # Windows folder fail if dot at the end
    if car_name.endswith("."):
        car_name = car_name[:-1]
    # Register the picture as jpg to download
    if ".png" in car_name:
        car_name.replace(".png", ".jpg")

    # Get brand name if composed
    if any([x for x in composed_brands if x in car_name]):
        car_brand_name = " ".join(car_name.split()[:2])
    else:
        car_brand_name = car_name.split()[0]
    # Specific car in game (registered as NFSW brand)
    if car_brand_name == "Battlefield":
        car_brand_name = "NFSW"
    # Append to all cars list
    if not car_brand_name in all_brands:
        all_brands.append(car_brand_name)
        brand_exists = True
    else:
        brand_exists = False
    # Get brand id
    car_brand_id = len(all_brands)

    # Get car description parsing all elements after comments
    car_desc_elements = html_car_page.find("div", id="mw-content-text").contents[3:]
    car_desc = ""
    for x in car_desc_elements:
        if x.name == "nav":
            break
        else:
            if x.name != "table" and x != "\n":
                try:
                    car_desc += x.text
                except:
                    car_desc += x
    car_desc.strip() # Remove first space and break lines

    # Get car pic using static img width
    car_pic_raw = html_car_page.find_all("img", width="250")[-1]
    car_pic_link = car_pic_raw["src"].split("/revision/")[0]
    car_pic = parse.unquote(car_pic_raw["data-image-key"])
    # Get dates below images (small tag)
    if len(html_car_page.find_all("small")) <= 2:
        # Shift by 2 with a car to get right values
        shift = 2
        # Dates correspond to the Battlefield Heroes SUV only
        car_date_start = "2012"
        car_date_end = "2015"
    else:
        shift = 0
        car_date_start = re.findall(r"<br/>(\d+)", str(html_car_page.find("small")))[0]
        car_date_end = re.findall(r"[-|>]\s?(\w+)</", str(html_car_page.find("small")))[0]
        # Happend only with a few cars
        if not car_date_start.isdigit():
            car_date_start = "2020"
        if not car_date_end.isdigit():
            car_date_end = "2020"
    car_data = html_car_page.find_all("tr")
    car_motor = car_data[3].text.strip()
    car_transmission = car_data[7 - shift].text.strip()[0]
    car_drivetrain = car_data[6 - shift].text.split()[0]

    # Download car pic
    os.makedirs(f"img/cars/{car_base_name}", exist_ok=True)
    request.urlretrieve(car_pic_link, f"img/cars/{car_base_name}/{car_pic}")
    # Convert png to jpg (1 Pagani car)
    if ".png" in car_pic:
        img = Image.open(f"img/cars/{car_base_name}/{car_pic}")
        img.save(f"img/cars/{car_base_name}/{car_pic[:-4]}.jpg", quality=100)
        os.remove(f"img/cars/{car_base_name}/{car_pic}")

    print(car_id, car_name, car_brand_id, car_desc, car_pic, car_date_start, car_date_end, car_motor, car_transmission, car_drivetrain)
    sql = "INSERT INTO cars (car_id, car_name, car_brand_id, car_desc, car_pic, car_date_start, car_date_end, car_motor, car_transmission, car_drivetrain) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
    val = (car_id, car_name, car_brand_id, car_desc, car_pic, car_date_start, car_date_end, car_motor, car_transmission, car_drivetrain)
    if use_db:
        cursor.execute(sql, val)

    # Check if brand is not fetched yet
    if brand_exists:
        # Some little edits for brand names
        if car_brand_name == "Marussia":
            alt_desc = car_brand_name + "_Motors"
        elif car_brand_name == "Ford Shelby":
            alt_desc = "Ford"
        elif car_brand_name == "Renault":
            alt_desc = car_brand_name + "_Sport"
        else:
            alt_desc = car_brand_name.replace(" ", "_")
        request_brand = requests.get(base_url + "/wiki/" + alt_desc)
        html_brand_page = bs4.BeautifulSoup(request_brand.content, "html.parser")
        # Get brand description parsing all elements after comments
        car_brand_desc_elements = html_brand_page.find("div", id="mw-content-text").contents[4:]
        car_brand_desc = ""
        for x in car_brand_desc_elements:
            if x.name == "table":
                break
            else:
                if x != "\n":
                    try:
                        car_brand_desc += x.text.replace("\n", " ")
                    except:
                        car_brand_desc += x
        # 2 cars are broken bc of NFSW brand
        if "was not found" in car_brand_desc:
            car_brand_desc = None
        if "NewPP limit report" in car_brand_desc:
            car_brand_desc = "This car is registered as a NFSW manufacturer."

        # Some other edits for image alt
        if car_brand_name == "Mitsubishi":
            alt_name = "Manufacturer " + car_brand_name + " Motors"
        elif car_brand_name == "NFSW":
            alt_name = "NFS Logo Twitter"
        elif car_brand_name == "Renault":
            alt_name = car_brand_name
        else:
            alt_name = "Manufacturer " + car_brand_name
        car_brand_raw = html_car_page.find_all("img", alt=alt_name)
        try:
            car_brand_link = car_brand_raw[0]["data-src"].split("/revision/")[0]
        except:
            car_brand_link = car_brand_raw[0]["src"].split("/revision/")[0]
        car_brand_pic = parse.unquote(car_brand_raw[0]["data-image-key"])
        # Renault only different name fix
        if car_brand_pic == "Renault.jpg":
            car_brand_pic = "Manufacturer_Renault.png"

        request.urlretrieve(car_brand_link, f"img/constructors/{car_brand_pic}")

        print(car_brand_id, car_brand_name, car_brand_desc, car_brand_pic)
        sql = "INSERT INTO constructors (brand_id, brand_name, brand_desc, brand_pic) VALUES (%s, %s, %s, %s)"
        val = (car_brand_id, car_brand_name, car_brand_desc, car_brand_pic)
        if use_db:
            cursor.execute(sql, val)

    # Get class icon (not needed if already have)
    car_class_raw = html_car_page.find_all("img", width="16")[0]
    try:
        car_class_link = car_class_raw["data-src"].split("/revision/")[0]
    except:
        car_class_link = car_class_raw["src"].split("/revision/")[0]

    car_class_name = parse.unquote(car_class_raw["data-image-key"])
    request.urlretrieve(car_class_link, f"assets/{car_class_name}")

    # Wrong method to get default car informations (changed in DB)
    """
    car_class = car_class_raw["alt"][-1]
    car_overall = car_data[2].text.split()[0]
    car_topspeed = car_data[2].text.split()[1]
    car_acceleration = car_data[2].text.split()[2]
    car_handling = car_data[2].text.split()[3]

    print(car_id, car_class, car_overall, car_topspeed, car_acceleration, car_handling, car_motor, car_transmission, car_drivetrain)
    sql = "INSERT INTO performance (car_id, car_class, overall, topspeed, acceleration, handling, motor, transmission, drivetrain) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)"
    val = (car_id, car_class, car_overall, car_topspeed, car_acceleration, car_handling, car_motor, car_transmission, car_drivetrain)
    if use_db:
        cursor.execute(sql, val)
    """

    cars_editions_table = html_car_page.find("th", scope="col").parent.parent
    cars_editions = cars_editions_table.find_all("td", rowspan="2")

    # Loop all editions in the table
    for i in range(len(cars_editions)):
        car_style_name = cars_editions[i].nextSibling.nextSibling.text.strip()
        all_colors.append(car_style_name)
        car_style_pic_link = cars_editions[i].find("a")["href"].split("/revision/")[0]
        if cars_editions[i].find("img") is not None:
            car_style_pic = parse.unquote(cars_editions[i].find("img")["data-image-key"])
        else:
            car_style_pic = None

        # All infos in table
        car_next_line = cars_editions[i].parent.findNext("tr")
        car_price = car_next_line.find_all("font")
        car_style_class = car_next_line.find_all("td")[2].find("img")["alt"][-1]
        car_style_overall = cars_editions[i].nextSibling.nextSibling.nextSibling.text.strip()
        car_style_data = cars_editions[i].nextSibling.nextSibling.nextSibling.nextSibling
        # Using regex to find perf numbers
        car_style_perf = re.findall(r">([0-9]{3})", str(car_style_data).strip())
        car_style_topspeed = car_style_perf[0]
        car_style_acceleration = car_style_perf[1]
        car_style_handling = car_style_perf[2]

        # Inexistant or missing prices, checking manually using min cash cost
        if str(car_price).find(",") != -1:
            a = [int(x.text.replace(",", "")) for x in car_price]
            if len(a) == 1:
                if a[0] < 62500:
                    car_style_price = None
                    car_style_price_sb = a[0]
                else:
                    car_style_price = a[0]
                    car_style_price_sb = None
            else:
                if a[0] < 62500:
                    car_style_price = a[1]
                    car_style_price_sb = a[0]
                else:
                    car_style_price = a[0]
                    car_style_price_sb = a[1]
        else:
            car_style_price = None
            car_style_price_sb = None

        # Download car pic edition
        if car_style_pic:
            request.urlretrieve(car_style_pic_link, f"img/cars/{car_base_name}/{car_style_pic}")
            if ".png" in car_style_pic:
                img = Image.open(f"img/cars/{car_base_name}/{car_style_pic}")
                img.save(f"img/cars/{car_base_name}/{car_style_pic[:-4]}.jpg")
                os.remove(f"img/cars/{car_base_name}/{car_style_pic}")

        print(car_id, car_style_name, car_style_pic, car_style_class, car_style_overall, car_style_topspeed, car_style_acceleration, car_style_handling, car_style_price, car_style_price_sb)
        sql = "INSERT INTO editions (car_id, style_name, style_pic, style_class, style_overall, style_topspeed, style_acceleration, style_handling, style_price, style_price_sb) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
        val = (car_id, car_style_name, car_style_pic, car_style_class, car_style_overall, car_style_topspeed, car_style_acceleration, car_style_handling, car_style_price, car_style_price_sb)
        if use_db:
            cursor.execute(sql, val)
            db.commit()

if use_db:
    # db.commit() # In case last one is skipped
    db.close()
    print("Done")
