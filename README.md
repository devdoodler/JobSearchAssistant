# Job search assistant
![jba logo](frontend/react_app/src/assets/jsa-logo-btr.svg)
### Assistant for jobseekers. Stores data and status of submitted job applications 

Information is stored in events, so any moment in the job application lifecycle can be recreated.  
  
Currently implemented events:  
` Add ` -> ` Submit `  
-> ` Reject `  
-> ` Schedule Interview ` -> ` Interview was held `

`Add` - add new application  
```json
{  
  "company": "Tech Company",  
  "position": "Software Engineer",  
  "details": "A description of the job."  
}  
```
`Submit` - the application was submited with date of submit and comment  
```json
{  
  "id": "e1341355-6f66-4f31-9812-d1a1927191e4",  
  "submitDate": "2025-02-28 12:00",  
  "comment": "Excited about this opportunity!"  
}  
```

`Reject` - the application was rejected with comment  
```json
{  
  "id": "e1341355-6f66-4f31-9812-d1a1927191e4",  
  "comment": "Unfortunately application was rejected"  
}  
```

`Schedule interview` - Scheduled Job Application Interview  
```json
{
  "id": "e1341355-6f66-4f31-9812-d1a1927191e4",
  "scheduleDate": "2025-02-28 12:00",
  "interviewType": "Other",
  "comment": ""
}  
```

`Interview was held` - Job Application Interview was held
```json
{
  "id": "e1341355-6f66-4f31-9812-d1a1927191e4",
  "interviewId": "84dd6866-b474-4b2b-8326-c8e32c5e8ddf",
  "comment": ""
}
```


## Versions

### 0.2.0 Pre-alpha

Added possibility to schedule interview.

### 0.1.0 Pre-alpha

It is possible to add new applications and submit them. On front you can see all applications.

## How to run in docker at dev env

```
docker-compose up --build
```
The app should be working at `localhost:3000`.   
  
You can make direct request to backend api to `localhost`. Possible request are in `job_application_requests.http` 

## Possible problems

### Stuck when running docker-compose

When you are running docker with `docker-compose up --build` its stuck on:

```
Attaching to mysql_db, react_frontend, symfony_backend
```
Check if you have xdebug disabled on each project.
Check if have already runned db on port :3307:

```
sudo lsof -i :3307
```

if there is then stop mysql:

```
sudo service mysql stop
```

or change the port mapping in `docker-compose.yml` (e.g., map 3306 to 3307 on the host).

### Error nginx 0.0.0.0:80: bind: address already in use

Probably you have working apache on that port. check: `sudo lsof -i :80`
And stop apache:
```
sudo service apache2 stop
```
## Bash

`docker exec -it symfony_backend bash`
