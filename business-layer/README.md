# Business Layer

## Representação do modelo de negócio

* Sem framework
* Testado unitariamente

### Assumptions

De modo geral, é formado abaixo a representação gráfica:

+-----------------+
|   Organization  |
|-----------------|
|                 |
|     Events      |
|-----------------|
|                 |
|  Participants   |
+-----------------+

Neste diagrama, a "Organization" contém "Events", e cada "Event" pode ter vários "Participants". Isso reflete a relação em que a "Organization" pode adicionar "Participants" aos seus "Events".

