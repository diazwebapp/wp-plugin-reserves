let rest_settings_cpanel = wp_rest_api_cpanel
let limit = 0
let current = 0
let total = 0

window.addEventListener('load', () => {
    const btns_nav_cpanel_ = document.querySelectorAll('.cpanel_body > .header_wp_tarifa_reserve_cpanel > nav button')
    const articles_ = document.querySelectorAll('.cpanel_body > section > article')
    const ventana_casas = document.querySelectorAll('#ventana_casas')
    const ventana_tarifas = document.querySelectorAll('#ventana_tarifas')
    const ventana_cargos_especiales = document.querySelectorAll('#ventana_cargos_especiales')
    const ventana_reservas = document.querySelectorAll('#ventana_reservas')

    const paginate_casas = document.getElementById('paginate_casas')
    const paginate_tarifas = document.getElementById('paginate_tarifas')
    const paginate_cargos_especiales = document.getElementById('paginate_cargos_especiales')
    const paginate_reservas = document.getElementById('paginate_reservas')

    //seleccionamos elementos html de adminitación de emails
    const input_email = document.getElementById('input_mail_insert')
    const btn_insert_email = document.getElementById('btn_mail_insert')

    for (let i = 0; i < btns_nav_cpanel_.length; i++) {
        btns_nav_cpanel_[i].addEventListener('click', () => {
            show_one_window({ html: articles_[i], css_class: 'show_article' })
        })
    }
   
    const ventanas = {
        ventana_casas: {
            boton: ventana_casas && ventana_casas.length > 0? ventana_casas[0]:null,
            vista: ventana_casas && ventana_casas.length > 0? ventana_casas[1]:null,
            more: paginate_casas?paginate_casas:null,
            cpt: 'casas'
        },
        ventana_tarifas: {
            boton: ventana_tarifas && ventana_tarifas.length > 0? ventana_tarifas[0]:null,
            vista: ventana_tarifas && ventana_tarifas.length > 0? ventana_tarifas[1]:null,
            more: paginate_tarifas?paginate_tarifas:null,
            cpt: 'tarifas'
        },
        ventana_cargos_especiales: {
            boton: ventana_cargos_especiales && ventana_cargos_especiales.length > 0? ventana_cargos_especiales[0]:null,
            vista: ventana_cargos_especiales && ventana_cargos_especiales.length > 0? ventana_cargos_especiales[1]:null,
            more: paginate_cargos_especiales?paginate_cargos_especiales:null,
            cpt: 'cargos_especiales'
        },
        ventana_reservas: {
            boton: ventana_reservas && ventana_reservas.length > 0? ventana_reservas[0]:null,
            vista: ventana_reservas && ventana_reservas.length > 0? ventana_reservas[1]:null,
            more: paginate_reservas?paginate_reservas:null,
            cpt: 'reservas'
        }
    }
    
    if(ventana_reservas){
        
        (async()=>{
            limit = 10
            show_one_window({ html:ventanas['ventana_reservas'].vista, css_class: 'show_article' })
            const btn_slot = ventanas['ventana_reservas'].more
            const { reservas, count } = await get_reservas({ limit })
            const http_resp = await get_emails()
            render_list_reservas({ reservas, cpt: 'reservas' })

            if(http_resp){
                render_list_emails({emails:http_resp.emails})
            }
    
            total = count
            
            pagi({btn_slot,cpt: 'reservas'})
            
        })()

        if(ventanas['ventana_reservas'].boton){
            ventanas['ventana_reservas'].boton.addEventListener('click', async () => {
                limit = 10
                const { reservas, count } = await get_reservas({ limit })
                render_list_reservas({ reservas, cpt: 'reservas' })
        
                const btn_slot = ventanas['ventana_reservas'].more
                total = count
                
                pagi({btn_slot,cpt: 'reservas'})
            })
        }
    }    

    if(ventanas['ventana_casas'].vista){
        ventanas['ventana_casas'].boton.addEventListener('click', async () => {
            limit = 10
            const { casas, count } = await get_casas({ limit })
            render_list_casas({ casas, cpt: 'casas' })
    
            const btn_slot = ventanas['ventana_casas'].more
            total = count
            
            pagi({btn_slot,cpt: 'casas'})
        })
    }
    if(ventanas['ventana_tarifas'].vista){
        ventanas['ventana_tarifas'].boton.addEventListener('click', async () => {
            limit = 10
            const { tarifas, count, casas } = await get_tarifas({ limit })
           
            const select_casas = ventanas['ventana_tarifas'].vista.querySelector('.cpanel_new_tarifas').querySelectorAll('div')[9].querySelector('select')
    
            select_casas.innerHTML = ''
            for(casa of casas){
                const option = document.createElement('option')
                option.value = casa.post_title
                option.textContent = casa.post_title
                select_casas.innerHTML += `<option selected value="${casa.post_title}">${casa.post_title}</option>`
            }
            
    
            render_list_tarifas({ tarifas, cpt: 'tarifas', casas })
            const btn_slot = ventanas['ventana_tarifas'].more
            total = count
            
            pagi({btn_slot,cpt: 'tarifas'})
        })
    }
    
    if(ventanas['ventana_cargos_especiales'].vista){
        ventanas['ventana_cargos_especiales'].boton.addEventListener('click', async () => {
            limit = 10
            const { cargos_especiales, count } = await get_cargos_especiales({ limit })
            render_list_cargos_especiales({ cargos_especiales, cpt: 'cargos_especiales' })

            const btn_slot = ventanas['ventana_cargos_especiales'].more
            total = count
            
            pagi({btn_slot,cpt: 'cargos_especiales'})

        })
    }
    if(btn_insert_email && input_email){
        btn_insert_email.addEventListener('click',async()=>{
            if(input_email.value=='') return alert('escribe un email')
            btn_insert_email.disabled = true
            const response = await insert_email({email:input_email.value})
            btn_insert_email.disabled = false
            if(response && response.status == 'ya existe') return alert('ya existe')
            if(response && response.status !== 'ya existe'){

                const http_resp = await get_emails()

                if(http_resp){
                    render_list_emails({emails:http_resp.emails})
                }
            }
        })
    }
})
// All methods
const pagi = async ({btn_slot,cpt})=>{
    const buton_more = document.createElement('button')
        buton_more.textContent = 'Ver más'
        buton_more.classList.add("button-primary")
        
        
        if(!btn_slot){
        
            if(limit <= total){
                if(cpt=='tarifas'){
                    const { tarifas } = await get_tarifas({ limit })
                    render_list_tarifas({ tarifas, cpt })
                    return
                }
                if(cpt=='reservas'){
                    
                    const { reservas } = await get_reservas({ limit })
                    render_list_reservas({ reservas, cpt: 'reservas' })
                    return
                }
                if(cpt=='cargos_especiales'){
                    const { cargos_especiales } = await get_cargos_especiales({ limit })
                    render_list_cargos_especiales({ cargos_especiales, cpt: 'cargos_especiales' })
                    return
                }
                if(cpt=='casas'){
                    const { casas } = await get_casas({ limit })
                    render_list_casas({ casas, cpt: 'casas' })
                    return
                }
            }
        }
       
        if(btn_slot && limit < total){
           
            btn_slot.innerHTML = ""
            
            buton_more.addEventListener('click',async()=>{
                limit+=3
                if(limit > total){
                    limit = total
                    if(limit <= total){
                        if(cpt=='tarifas'){
                            const { tarifas } = await get_tarifas({ limit })
                            render_list_tarifas({ tarifas, cpt })
                            return
                        }
                        if(cpt=='reservas'){
                            const { reservas } = await get_reservas({ limit })
                            render_list_reservas({ reservas, cpt: 'reservas' })
                            return
                        }
                        if(cpt=='cargos_especiales'){
                            const { cargos_especiales } = await get_cargos_especiales({ limit })
                            render_list_cargos_especiales({ cargos_especiales, cpt: 'cargos_especiales' })
                            return
                        }
                        if(cpt=='casas'){
                            const { casas } = await get_casas({ limit })
                            render_list_casas({ casas, cpt: 'casas' })
                            return
                        }
                    }
                }
                
                if(limit <= total){
                    if(cpt=='tarifas'){
                        const { tarifas } = await get_tarifas({ limit })
                        render_list_tarifas({ tarifas, cpt })
                        return
                    }
                    if(cpt=='reservas'){
                        const { reservas } = await get_reservas({ limit })
                        render_list_reservas({ reservas, cpt: 'reservas' })
                        return
                    }
                    if(cpt=='cargos_especiales'){
                        const { cargos_especiales } = await get_cargos_especiales({ limit })
                        render_list_cargos_especiales({ cargos_especiales, cpt: 'cargos_especiales' })
                        return
                    }
                    if(cpt=='casas'){
                        const { casas } = await get_casas({ limit })
                        render_list_casas({ casas, cpt: 'casas' })
                        return
                    }
                }
            })
            btn_slot.appendChild(buton_more)
        }
}
const hide_all_windows = (articles_) => {
    for (article of articles_) {
        article.classList.remove('show_article')
    }
}
const show_one_window = ({ html, css_class }) => {
    //ventanas del cpanel
    const articles_ = document.querySelectorAll('.cpanel_body > section > article')
   
    hide_all_windows(articles_)
    
    html.classList.add(css_class)
}


const render_list_emails = ({emails}) => {
    const ul_emails = document.getElementById('ul_emails')
    const template = document.getElementById("template_emails_list").content
    const fragment = document.createDocumentFragment()

    if (template) {
        ul_emails.innerHTML = ''
        for (data of emails) {
            template.querySelector('li b').textContent = data.email
            template.querySelector('li button').setAttribute('data',data.ID)

            const clone = template.cloneNode(true)
            fragment.appendChild(clone)
        }
        ul_emails.appendChild(fragment)

    }
}

const render_list_tarifas = ({ tarifas, cpt }) => {
    const html_tbody = document.querySelector(`.table_${cpt} tbody`)
    const tbody_template = document.querySelector(`#template_tbody_${cpt}_content`).content
    const fragment = document.createDocumentFragment()
    tarifas.reverse()
    if (tbody_template) {
        html_tbody.innerHTML = ''
        for (casa of tarifas) {
            let monto = parseFloat(casa.meta['day_0']) + parseFloat(casa.meta['day_1'])+parseFloat(casa.meta['day_2']) +parseFloat(casa.meta['day_3'])+parseFloat(casa.meta['day_4'])+parseFloat(casa.meta['day_5'])+parseFloat(casa.meta['day_6'])
            tbody_template.querySelectorAll('tr td')[0].textContent = 1
            tbody_template.querySelectorAll('tr td')[1].textContent = casa.ID
            tbody_template.querySelectorAll('tr td')[2].textContent = casa.post_title
            //Dias de la semana
            tbody_template.querySelectorAll('tr td')[3].textContent = casa.meta['day_0']
            tbody_template.querySelectorAll('tr td')[4].textContent = casa.meta['day_1']
            tbody_template.querySelectorAll('tr td')[5].textContent = casa.meta['day_2']
            tbody_template.querySelectorAll('tr td')[6].textContent = casa.meta['day_3']
            tbody_template.querySelectorAll('tr td')[7].textContent = casa.meta['day_4']
            tbody_template.querySelectorAll('tr td')[8].textContent = casa.meta['day_5']
            tbody_template.querySelectorAll('tr td')[9].textContent = casa.meta['day_6']
            tbody_template.querySelectorAll('tr td')[10].textContent = monto.toLocaleString('sp-ES',{currency:'eur'})
            tbody_template.querySelectorAll('tr td')[11].textContent = casa.meta['min_huespedes']
            tbody_template.querySelectorAll('tr td')[12].textContent = casa.meta['max_huespedes']
            tbody_template.querySelectorAll('tr td')[13].textContent = casa.post_status
            tbody_template.querySelectorAll('tr td')[14].textContent = casa.meta['casa']
            //acciones
            tbody_template.querySelectorAll('tr td button')[0].id = casa.ID
            tbody_template.querySelectorAll('tr td button')[1].id = casa.ID
            

            const clone = tbody_template.cloneNode(true)
            fragment.appendChild(clone)
        }
        html_tbody.appendChild(fragment)

    }
}
const render_list_reservas = ({ reservas, cpt }) => {
    const html_tbody = document.querySelector(`.table_${cpt} tbody`)
    const tbody_template = document.querySelector(`#template_tbody_${cpt}_content`).content
    const fragment = document.createDocumentFragment()
    
    if (tbody_template) {
        html_tbody.innerHTML = ''
        for (let i=0;i <reservas.length;i++) {
            tbody_template.querySelectorAll('tr td')[0].textContent = i+1
            tbody_template.querySelectorAll('tr td')[1].textContent = reservas[i].ID
            tbody_template.querySelectorAll('tr td')[2].textContent = reservas[i].post_title
            tbody_template.querySelectorAll('tr td')[3].textContent = reservas[i].meta['huespedes']
            tbody_template.querySelectorAll('tr td')[4].textContent = reservas[i].meta['cliente']
            tbody_template.querySelectorAll('tr td')[5].textContent = reservas[i].meta['telefono']
            tbody_template.querySelectorAll('tr td')[6].textContent = reservas[i].meta['correo']
            tbody_template.querySelectorAll('tr td')[7].textContent = reservas[i].meta['checkin']
            tbody_template.querySelectorAll('tr td')[8].textContent = reservas[i].meta['checkout']
            tbody_template.querySelectorAll('tr td')[9].textContent = reservas[i].meta['monto']
            tbody_template.querySelectorAll('tr td')[10].textContent = reservas[i].meta['observaciones']
            tbody_template.querySelectorAll('tr td')[11].textContent = reservas[i].post_status 

            //acciones
            tbody_template.querySelectorAll('tr td button')[0].id = reservas[i].ID
            tbody_template.querySelectorAll('tr td button')[1].id = reservas[i].ID

            const clone = tbody_template.cloneNode(true)
            fragment.appendChild(clone)
        }
        html_tbody.appendChild(fragment)

    }
}
const render_list_cargos_especiales = ({ cargos_especiales, cpt }) => {
    const html_tbody = document.querySelector(`.table_${cpt} tbody`)
    const tbody_template = document.querySelector(`#template_tbody_${cpt}_content`).content
    const fragment = document.createDocumentFragment()
    cargos_especiales.reverse()
    if (tbody_template) {
        html_tbody.innerHTML = ''
        for (casa of cargos_especiales) {
            tbody_template.querySelectorAll('tr td')[0].textContent = 1
            tbody_template.querySelectorAll('tr td')[1].textContent = casa.ID
            tbody_template.querySelectorAll('tr td')[2].textContent = casa.post_title
            tbody_template.querySelectorAll('tr td')[3].textContent = casa.meta['fecha_especial']
            tbody_template.querySelectorAll('tr td')[4].textContent = casa.meta['asunto']
            tbody_template.querySelectorAll('tr td')[5].textContent = casa.meta['monto']
            tbody_template.querySelectorAll('tr td')[6].textContent = casa.post_status

            //acciones
            tbody_template.querySelectorAll('tr td button')[0].id = casa.ID
            tbody_template.querySelectorAll('tr td button')[1].id = casa.ID
            const clone = tbody_template.cloneNode(true)
            fragment.appendChild(clone)
        }
        html_tbody.appendChild(fragment)
    }
}
const render_list_casas = ({ casas, cpt }) => {
    const html_tbody = document.querySelector(`.table_${cpt} tbody`)
    const tbody_template = document.querySelector(`#template_tbody_${cpt}_content`).content
    const fragment = document.createDocumentFragment()
    casas.reverse()
    if (tbody_template) {
        html_tbody.innerHTML = ''
        for (casa of casas) {
            tbody_template.querySelectorAll('tr td')[0].textContent = 1
            tbody_template.querySelectorAll('tr td')[1].textContent = casa.ID
            tbody_template.querySelectorAll('tr td')[2].textContent = casa.post_title
            tbody_template.querySelectorAll('tr td')[3].textContent = casa.post_status

            //acciones
            tbody_template.querySelectorAll('tr td button')[0].id = casa.ID
            tbody_template.querySelectorAll('tr td button')[1].id = casa.ID
            const clone = tbody_template.cloneNode(true)
            fragment.appendChild(clone)
        }
        html_tbody.appendChild(fragment)
    }
}
const render_form_items = ({ tarifa, cpt }) => {
    const html_form = document.querySelector(`.container_cpanel_edit_${cpt} .cpanel_form`)
    const form_template = document.querySelector(`#template_form_${cpt}_content`).content
    const fragment = document.createDocumentFragment()
    if (form_template) {
        html_form.innerHTML = ''
        if(cpt=='tarifas'){
            form_template.querySelectorAll('div')[0].querySelector('input').value = tarifa[1].textContent
            form_template.querySelectorAll('div')[1].querySelector('input').value = tarifa[3].textContent
            form_template.querySelectorAll('div')[2].querySelector('input').value = tarifa[4].textContent
            form_template.querySelectorAll('div')[3].querySelector('input').value = tarifa[5].textContent
            form_template.querySelectorAll('div')[4].querySelector('input').value = tarifa[6].textContent
            form_template.querySelectorAll('div')[5].querySelector('input').value = tarifa[7].textContent
            form_template.querySelectorAll('div')[6].querySelector('input').value = tarifa[8].textContent
            form_template.querySelectorAll('div')[7].querySelector('input').value = tarifa[9].textContent
            form_template.querySelectorAll('div')[8].querySelector('input').value = tarifa[11].textContent
            form_template.querySelectorAll('div')[9].querySelector('input').value = tarifa[12].textContent
            form_template.querySelectorAll('div')[10].querySelector('select').querySelectorAll('option')[0].value = tarifa[13].textContent
            form_template.querySelectorAll('div')[10].querySelector('select').querySelectorAll('option')[0].textContent = tarifa[13].textContent
            form_template.querySelectorAll('div')[11].querySelector('select').querySelectorAll('option')[0].value = tarifa[14].textContent
            form_template.querySelectorAll('div')[11].querySelector('select').querySelectorAll('option')[0].textContent = tarifa[14].textContent
            const clone = form_template.cloneNode(true)
            fragment.appendChild(clone)
        }
        if(cpt=='reservas'){
            form_template.querySelectorAll('div')[0].querySelector('input').value = tarifa[1].textContent
            form_template.querySelectorAll('div')[1].querySelector('input').value = tarifa[2].textContent
            form_template.querySelectorAll('div')[2].querySelector('input').value = tarifa[3].textContent
            form_template.querySelectorAll('div')[3].querySelector('input').value = tarifa[4].textContent
            form_template.querySelectorAll('div')[4].querySelector('input').value = tarifa[5].textContent
            form_template.querySelectorAll('div')[5].querySelector('input').value = tarifa[6].textContent
            form_template.querySelectorAll('div')[6].querySelector('input').value = tarifa[7].textContent
            form_template.querySelectorAll('div')[7].querySelector('input').value = tarifa[8].textContent
            form_template.querySelectorAll('div')[8].querySelector('input').value = tarifa[9].textContent
            form_template.querySelectorAll('div')[9].querySelector('input').value = tarifa[10].textContent
            form_template.querySelectorAll('div')[10].querySelector('select').querySelectorAll('option')[0].value = tarifa[11].textContent
            form_template.querySelectorAll('div')[10].querySelector('select').querySelectorAll('option')[0].textContent = tarifa[11].textContent
            const clone = form_template.cloneNode(true)
            fragment.appendChild(clone)
        }
        if(cpt=='cargos_especiales'){
            form_template.querySelectorAll('div')[0].querySelector('input').value = tarifa[1].textContent
            form_template.querySelectorAll('div')[1].querySelector('input').value = tarifa[2].textContent
            form_template.querySelectorAll('div')[2].querySelector('input').value = tarifa[3].textContent
            form_template.querySelectorAll('div')[3].querySelector('input').value = tarifa[4].textContent
            form_template.querySelectorAll('div')[4].querySelector('input').value = tarifa[5].textContent
            form_template.querySelectorAll('div')[5].querySelector('select').querySelectorAll('option')[0].value = tarifa[6].textContent
            form_template.querySelectorAll('div')[5].querySelector('select').querySelectorAll('option')[0].textContent = tarifa[6].textContent
            const clone = form_template.cloneNode(true)
            fragment.appendChild(clone)
        }
        if(cpt=='casas'){
            form_template.querySelectorAll('div')[0].querySelector('input').value = tarifa[1].textContent
            form_template.querySelectorAll('div')[1].querySelector('input').value = tarifa[2].textContent
            form_template.querySelectorAll('div')[2].querySelector('select').querySelectorAll('option')[0].value = tarifa[3].textContent
            form_template.querySelectorAll('div')[2].querySelector('select').querySelectorAll('option')[0].textContent = tarifa[3].textContent
            const clone = form_template.cloneNode(true)
            fragment.appendChild(clone)
        }
        html_form.appendChild(fragment)
    }
}
const clear_form_items = ({ cpt }) => {
    const html_form = document.querySelector(`.container_cpanel_edit_${cpt} .cpanel_form`)
    html_form.innerHTML = ""
}
const view_item = (element) => {
    const tds = element.parentElement.parentElement.querySelectorAll('td')
    render_form_items({tarifa:tds, cpt:element.attributes.data.textContent })
}
const update_item = async (element) => {
    const div_form = element.parentElement.parentElement
    const cpt = element.attributes.data.textContent

    if(cpt == 'tarifas'){
        const body_item={}
        for(input of div_form.querySelectorAll('input')){
            if(input.value == ''){
                if(input.name != 'min_huespedes' && input.name != 'max_huespedes' && input.name != 'titulo'){
                    return alert('rellene los dias de la semana ')
                }
                return alert('rellene todos los campos '+input.name)
            }
            body_item[input.name] = input.value
        }
        const select = div_form.querySelectorAll('select')
        body_item[select[0].name] = select[0].value
        body_item[select[1].name] = select[1].value
        
        div_form.innerHTML += `<span class="wp_tarifas_reserve_loader_cpanel" >Cargando...</span>`
        const {titulo,status} = await update_item_handler({cpt,body_item})
        const { tarifas } = await get_tarifas({ limit })
        render_list_tarifas({ tarifas, cpt: 'tarifas' })
        const loader = div_form.querySelector('.wp_tarifas_reserve_loader_cpanel')  
        loader.textContent = status? titulo+' actualizado!':'error' 
        setTimeout(()=>{
            loader.remove()
        },2000)
        clear_form_items({cpt})                                           
    }
    if(cpt == 'reservas'){
        const body_item={}
        for(input of div_form.querySelectorAll('input')){
            if(input.value == ''){
                if(input.name != 'observaciones'){
                    return alert('rellene todos los campos '+input.name)
                }
            }
            body_item[input.name] = input.value
        }
        const select = div_form.querySelectorAll('select')
        body_item[select[0].name] = select[0].value
        
        div_form.innerHTML += `<span class="wp_tarifas_reserve_loader_cpanel" >Cargando...</span>`
        const {titulo,status} = await update_item_handler({cpt,body_item})
        const { reservas } = await get_reservas({ limit })
        render_list_reservas({ reservas, cpt: 'reservas' })
        const loader = div_form.querySelector('.wp_tarifas_reserve_loader_cpanel')  
        loader.textContent = status? titulo+' actualizado!':'error' 
        setTimeout(()=>{
            loader.remove()
        },2000)
        clear_form_items({cpt})                                           
    }
    if(cpt == 'cargos_especiales'){
        const body_item={}
        for(input of div_form.querySelectorAll('input')){
            if(input.value == ''){
                return alert('rellene todos los campos '+input.name)
            }
            body_item[input.name] = input.value
        }
        const select = div_form.querySelectorAll('select')
        body_item[select[0].name] = select[0].value
        
        div_form.innerHTML += `<span class="wp_tarifas_reserve_loader_cpanel" >Cargando...</span>`
        const {titulo,status} = await update_item_handler({cpt,body_item})
        const { cargos_especiales } = await get_cargos_especiales({ limit })
        render_list_cargos_especiales({ cargos_especiales, cpt: 'cargos_especiales' })
        const loader = div_form.querySelector('.wp_tarifas_reserve_loader_cpanel')  
        loader.textContent = status? titulo+' actualizado!':'error' 
        setTimeout(()=>{
            loader.remove()
        },2000)  
        clear_form_items({cpt})                                         
    }
    if(cpt == 'casas'){
        const body_item={}
        for(input of div_form.querySelectorAll('input')){
            if(input.value == ''){
                return alert('rellene todos los campos '+input.name)
            }
            body_item[input.name] = input.value
        }
        const select = div_form.querySelectorAll('select')
        body_item[select[0].name] = select[0].value
        
        div_form.innerHTML += `<span class="wp_tarifas_reserve_loader_cpanel" >Cargando...</span>`
        const {titulo,status} = await update_item_handler({cpt,body_item})
        const { casas } = await get_casas({ limit })
        render_list_casas({ casas, cpt: 'casas' })
        const loader = div_form.querySelector('.wp_tarifas_reserve_loader_cpanel')  
        loader.textContent = status? titulo+' actualizado!':'error' 
        setTimeout(()=>{
            loader.remove()
        },2000)  
        clear_form_items({cpt})                                         
    }
}
const delete_email=async(element)=>{
    const email_id = element.attributes.data.textContent
    element.disabled = true
    element.textContent = 'espere...'
    const response = await delete_email_handler({email_id})

    element.disabled = false
    element.textContent = 'borrar'

    if(!response){
        alert('algo salió mal')
    }else{
        const http_resp = await get_emails()
        if(http_resp){
            render_list_emails({emails:http_resp.emails})
        }
    }
    
}
const delete_item = async (element) => {
    const id = element.id
    const cpt = element.attributes.data.textContent

    element.disabled = true
    element.textContent = 'Espere...'

    if(cpt == 'tarifas'){
        
        await delete_item_handler({cpt,id})
        
        const { tarifas } = await get_tarifas({ limit })
        render_list_tarifas({ tarifas, cpt: 'tarifas' })
                                                  
    }
    if(cpt == 'reservas'){
        await delete_item_handler({cpt,id})
        const { reservas } = await get_reservas({ limit})
        render_list_reservas({ reservas, cpt: 'reservas' })
                                                
    }
    if(cpt == 'cargos_especiales'){
        await delete_item_handler({cpt,id})
        const { cargos_especiales } = await get_cargos_especiales({ limit })
        render_list_cargos_especiales({ cargos_especiales, cpt: 'cargos_especiales' })
                                                
    }
    if(cpt == 'casas'){
        await delete_item_handler({cpt,id})
        const { casas } = await get_casas({ limit })
        render_list_casas({ casas, cpt: 'casas' })
                                                
    }
    element.disabled = false
    element.textContent = 'Borrar'
}
const create_item = async(element)=>{
    const div_form = element.parentElement.parentElement
    const cpt = element.attributes.data.textContent

    if(cpt == 'tarifas'){
        const body_item={}
        for(input of div_form.querySelectorAll('input')){
            if(input.value == ''){
                if(input.name != 'min_huespedes' && input.name != 'max_huespedes'){
                    return alert('rellene los dias de la semana ')
                }
                return alert('rellene todos los campos '+input.name)
            }
            body_item[input.name] = input.value
            const select = div_form.querySelectorAll('select')
            body_item[select[0].name] = select[0].value
        }
        
        div_form.innerHTML += `<span class="wp_tarifas_reserve_loader_cpanel" >Cargando...</span>`
        const {titulo,status,id} = await create_item_handler({cpt,body_item}) 
        const loader = div_form.querySelector('.wp_tarifas_reserve_loader_cpanel')  
        loader.textContent = status? titulo+' creado!':'error' 
        const { tarifas, count } = await get_tarifas({ limit })
        render_list_tarifas({ tarifas, cpt: 'tarifas' })
        total = count
        pagi({cpt: 'tarifas'})
        
        //window.location = rest_settings_cpanel.home_url+`/wp-admin/post.php?post=${id}&action=edit`
        setTimeout(()=>{
            loader.remove()
        },2000)                                               
    }
    if(cpt == 'cargos_especiales'){
        const body_item={}
        for(input of div_form.querySelectorAll('input')){
            if(input.value == ''){
               return alert('rellene todos los campos '+input.name)
            }
            body_item[input.name] = input.value
        }
        div_form.innerHTML += `<span class="wp_tarifas_reserve_loader_cpanel" >Cargando...</span>`
        const {titulo,status} = await create_item_handler({cpt,body_item}) 
        const loader = div_form.querySelector('.wp_tarifas_reserve_loader_cpanel')  
        loader.textContent = status? titulo+' creado!':'error' 
        const { cargos_especiales, count } = await get_cargos_especiales({ limit })
        render_list_cargos_especiales({ cargos_especiales, cpt: 'cargos_especiales' })
        total = count
        pagi({cpt: 'cargos_especiales'})
        setTimeout(()=>{
            loader.remove()
        },3000)              
    }
    if(cpt == 'casas'){
        const body_item={}
        for(input of div_form.querySelectorAll('input')){
            if(input.value == ''){
               return alert('rellene todos los campos '+input.name)
            }
            body_item[input.name] = input.value
        }
        
        div_form.innerHTML += `<span class="wp_tarifas_reserve_loader_cpanel" >Cargando...</span>`
        const {titulo,status} = await create_item_handler({cpt,body_item}) 
        
        const loader = div_form.querySelector('.wp_tarifas_reserve_loader_cpanel')  
        loader.textContent = status=='ok'? titulo+' creado!':status=='fail'?'Ya existe esta casa':'error' 
        const { casas, count } = await get_casas({ limit })
        render_list_casas({ casas, cpt: 'casas' })
        total = count
        pagi({cpt: 'casas'})
        setTimeout(()=>{
            loader.remove()
        },3000)              
    }
}
const close_edit_form = async (element) => {
    const cpt = element.attributes.data.textContent
    clear_form_items({cpt}) 
}
/** HTTP methods */
const get_tarifas = async ({ limit }) => { // obtiene las habitaciones del servidor
    
    try {
        const { casas } = await get_casas({limit:999999999999})
        const req_h = await fetch(`${rest_settings_cpanel.root}wp-reserves-system/v1/inmuebles?${limit ? 'limit=' + limit : ''}`)
        const { tarifas, count } = await req_h.json()
        
        return { tarifas, count, casas }
    } catch (error) {
        console.error(error)
        
        return
    }
}
const get_reservas = async ({ limit, status }) => { // obtiene las reservaciones del servidor
    
    try {
        const req_h = await fetch(`${rest_settings_cpanel.root}wp-reserves-system/v1/reservas?limit=${limit}${status ? '&status=' + status : ''}`)
        
        return await req_h.json()
    } catch (error) {
        console.error(error)
        
        return
    }
}
const get_cargos_especiales = async ({ limit, status }) => { // obtiene las cargoc_especialesiones del servidor
    
    try {
        const req_h = await fetch(`${rest_settings_cpanel.root}wp-reserves-system/v1/cargos_especiales?limit=${limit}${status ? '&status=' + status : ''}`)
        
        return await req_h.json()
    } catch (error) {
        console.error(error)
        
        return
    }
}
const get_casas = async ({ limit, status }) => { // obtiene las cargoc_especialesiones del servidor
    
    try {
        const req_h = await fetch(`${rest_settings_cpanel.root}wp-reserves-system/v1/casas?limit=${limit}${status ? '&status=' + status : ''}`)
               
        return await req_h.json()
    } catch (error) {
        console.error(error)
        
        return
    }
}
const create_item_handler = async({cpt,body_item})=>{
   
    const req_h = await fetch(`${rest_settings_cpanel.root}wp-reserves-system/v1/items`,{
        method:'POST',
        body:JSON.stringify({cpt,body_item}),
        headers:{
            "content-type":"application/json"
        }
    })
     
    return await req_h.json()
}
const update_item_handler = async({cpt,body_item})=>{
    
    const req_h = await fetch(`${rest_settings_cpanel.root}wp-reserves-system/v1/items`,{
        method:'PUT',
        body:JSON.stringify({cpt,body_item}),
        headers:{
            "content-type":"application/json"
        }
    })
    
    return await req_h.json()
}
const delete_item_handler = async ({cpt,id}) => { // obtiene las habitaciones del servidor
    
    const confirmar = window.confirm('Desea eliminar este elemento?')
    if(confirmar){
       
        const req_h = await fetch(`${rest_settings_cpanel.root}wp-reserves-system/v1/items`,{
            method:'delete',
            body:JSON.stringify({cpt,id}),
            headers:{
                "content-type":"application/json"
            }
        })
        return await req_h.json()
       
    }
}

const get_emails = async()=>{
    try {
        const req_h = await fetch(`${rest_settings_cpanel.root}wp-reserves-system/v1/emails`)
        if(req_h.status !==200) return false
        const data = await req_h.json()
        return data
    } catch (error) {
        console.error(error)
        return false
    }
}

const insert_email = async({email})=>{
    const req_h = await fetch(`${rest_settings_cpanel.root}wp-reserves-system/v1/new_email`,{
        method:'post',
        body:JSON.stringify({email}),
        headers:{
            "content-type":"application/json"
        }
    })
    if(req_h.status !== 200) return false
    return await req_h.json()
}
const delete_email_handler = async ({email_id})=>{
    const req_h = await fetch(`${rest_settings_cpanel.root}wp-reserves-system/v1/delete_email`,{
        method:'delete',
        body:JSON.stringify({email_id}),
        headers:{
            "content-type":"application/json"
        }
    })
    if(req_h.status !== 200) return false
    return await req_h.json()
}