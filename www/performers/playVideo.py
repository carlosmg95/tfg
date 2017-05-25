"""
Example that shows how the new Python 2 socket client can be used.

Functions called in this example are blocking which means that
the function doesn't return as long as no result was received.
"""

from __future__ import print_function
import time
import sys
import logging

import pychromecast
import pychromecast.controllers.youtube as youtube

if '--show-debug' in sys.argv:
    logging.basicConfig(level=logging.DEBUG)

casts = pychromecast.get_chromecasts()
if len(casts) == 0:
    print('No Devices Found')
    exit()
cast = casts[0]

yt = youtube.YouTubeController()
cast.register_handler(yt)

print()
print(cast.device)
time.sleep(1)
print()
print(cast.status)
print()
print(cast.media_controller.status)
print()

if '--show-status-only' in sys.argv:
    sys.exit()

if not cast.is_idle:
    print('Killing current running app')
    cast.quit_app()
    time.sleep(5)

print('Playing media')
if 'rain' in sys.argv:
    cast.play_media(('http://res.cloudinary.com/carlosmg95/video/upload/v1495465448/chromecast/videos/rain.mp4'), 'video/mp4')
elif 'space' in sys.argv:
    cast.play_media(('http://res.cloudinary.com/carlosmg95/video/upload/v1495464576/chromecast/videos/space.mp4'), 'video/mp4')
elif 'fire' in sys.argv:
    cast.play_media(('http://res.cloudinary.com/carlosmg95/video/upload/v1495463544/chromecast/videos/fire.mp4'), 'video/mp4')
elif 'snow' in sys.argv:
    cast.play_media(('http://trailers.divx.com/divx_prod/profiles/WiegelesHeliSki_DivXPlus_19Mbps.mkv'), 'video/mkv')
else:
    cast.play_media(
    ('http://commondatastorage.googleapis.com/gtv-videos-bucket/'
     'sample/BigBuckBunny.mp4'), 'video/mp4')