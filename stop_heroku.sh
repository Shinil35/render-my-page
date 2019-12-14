#!/bin/bash
heroku maintenance:on
heroku ps:scale web=0
