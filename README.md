# Matomo Courier Plugin

## Description

Courier provides the possibility to setup different integrations for your
Matomo instance, like webhhoks and Slack.

Courier does nothing on it's own - it has configurable integrations that you 
could use from other plugins. For instance to use with Scenario, a plugin that
acts if a certain scenario is true or false.

## Webhook format
The web hook integration sends a curl POST to the integration, with message in json-
format. Output is like:
```json
{"sender":"Matomo","message":"Bar is the new Foo"}
```
