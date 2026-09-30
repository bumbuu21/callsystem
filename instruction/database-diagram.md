# Дуудлага бүртгэлийн системийн өгөгдлийн сангийн диаграмм

Энэ диаграммыг тайлангийн “Өгөгдлийн сангийн диаграмм” хэсэгт ашиглана. Mermaid дэмждэг редактор эсвэл https://mermaid.live дээр доорх кодыг оруулж зураг болгон экспортолж болно.

```mermaid
erDiagram
    USERS ||--o| AGENTS : "инженерийн бүртгэлтэй"
    USERS ||--o{ CALLS : "дуудлага илгээнэ"
    AGENTS ||--o{ CALLS : "дуудлага хариуцна"
    USERS ||--o{ SYSTEM_LOGS : "үйлдэл хийнэ"

    USERS {
        bigint id PK
        varchar name
        varchar phone
        varchar email UK
        varchar username UK
        varchar password
        varchar role "admin/operator/agent/customer"
        varchar status "active/inactive"
        varchar aimag_code
        varchar aimag_name
        varchar soum_code
        varchar soum_name
    }

    AGENTS {
        bigint id PK
        bigint user_id FK
        varchar name
        varchar title
        varchar phone
    }

    CALLS {
        bigint id PK
        varchar caller_name
        varchar caller_phone
        varchar call_type "network/program/hardware/other"
        varchar call_from
        text description
        varchar status "submitted/accepted/resolved"
        bigint user_id FK
        varchar api_username
        bigint agent_id FK
        varchar photo_path
        timestamp requested_at
        timestamp accepted_at
        timestamp resolved_at
        text agent_description
        text client_comment
    }

    SYSTEM_LOGS {
        bigint id PK
        bigint user_id FK
        varchar action
        varchar ip_address
        timestamp done_at
        integer last_activity
    }
```

## Хүснэгтүүдийн зорилго

| Хүснэгт | Зорилго |
|---|---|
| `users` | Харилцагч, оператор, инженер, хэлтсийн дарга/админы нэвтрэх болон үндсэн мэдээлэл. |
| `agents` | Инженерийн ажлын мэдээлэл; `users.id`-тай `user_id` талбараар холбогдоно. |
| `calls` | Дуудлага, төлөв, тайлбар, хариуцсан инженер, огноо, сэтгэгдэл. |
| `system_logs` | Хэрэглэгчийн хийсэн үйлдлийн аудит лог. |

## Хэлтсийн дарга / админ

Админ нь `users.role = admin` эрхтэй хэрэглэгч. Туршилтын бүртгэл:

```text
Нэвтрэх нэр: admin
Нууц үг: password123
Нэр: Хэлтсийн дарга (Админ)
```

Админ бүх дуудлагыг харах, шүүх, тайлан харах, хэрэглэгч/инженер нэмэх, хэрэглэгчийг идэвхгүй болгох, системийн логийг харах эрхтэй.
