# Message Queue App

---

### How to run on Docker
1. clone the repo
2. cd into the repo
3. run `.\vendor\bin\sail up`

### How to run seeder (for testing)
1. run `php artisan db:seed`

---

## API

**Headers** (apply to all API route):

| Key          | Value            |
|--------------|------------------|
| Accept       | application/json |
| Content-Type | application/json |

### 1. Get all messages
HTTP API to get all SMS messages in the queue in JSON format. <br>
**Method:** `GET` <br>
**Url:** `localhost/api/messages` <br>
**Body:** none <br>

**Response Example:**
> ```
> [
>     {
>         "datetime": "2022-07-23T08:27:29+00:00",
>         "message": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus feugiat odio ac enim vulputate, at fermentum felis consectetur. Suspendisse nec est tempus nam."
>     },
>     {
>         "datetime": "2022-07-23T08:27:30+00:00",
>         "message": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus feugiat odio ac enim vulputate, at fermentum felis consectetur. Suspendisse nec est tempus nam."
>     },
>     {
>         "datetime": "2022-07-23T08:27:31+00:00",
>         "message": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus feugiat odio ac enim vulputate, at fermentum felis consectetur. Suspendisse nec est tempus nam."
>     }
> ]
> ```
> **Status Code:** 200 OK

### 2. Get total messages
HTTP API to get the total number of messages in the queue. <br>
**Method:** `GET` <br>
**Url:** `localhost/api/messages/total` <br>
**Body:** none <br>

**Response Example:**
> ```
> 3
> ```
> **Status Code:** 200 OK

### 3. Queue message
HTTP API to insert an SMS Message in the queue. <br>
**Method:** `POST` <br>
**Url:** `localhost/api/messages/store` <br>
**Body:** `JSON` <br>

| Key      | Type   | Condition                         |
|----------|--------|-----------------------------------|
| message  | string | required & maximum 160 characters |
| datetime | string | optional; ISO8601 format          |

**Body Example:**
```
{
"datetime": "2022-07-21T19:04:29+00:00",
"message": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus feugiat odio ac enim vulputate, at fermentum felis consectetur. Suspendisse nec est tempus nam."
}
```

**Response Example:**
> ```
> Queue the message success.
> ```
> **Status Code:** 201 Created

> ```
> Queue the message fail.
> ```
> **Status Code:** 422 Unprocessable Content 
 
### 4. Consume a message
HTTP API to consume an SMS Message from the queue and returns it in JSON format. <br>
**Method:** `GET` <br>
**Url:** `localhost/api/messages/consume` <br>
**Body:** none <br>

**Response Example:**
> ```
> {
>     "datetime": "2022-07-21T19:04:29+00:00",
>     "message": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus feugiat odio ac enim vulputate, at fermentum felis consectetur. Suspendisse nec est tempus nam."
> }
> ```
> **Status Code:** 202 Accepted

> ```
> Consume message from queue fail.
> ```
> **Status Code:** 422 Unprocessable Content

---

## Caveat
Performance not scalable because each message consume will rewrite entire file (`queue.txt`) to remove it from the queue.
