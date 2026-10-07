@extends('layouts.dashboard')

@section('content')
<style>
    #selectDiagnosisElement + .select2-container .select2-selection--single {
        height: 40px !important;
        line-height: 40px !important;
    }
    #selectDiagnosisElement + .select2-container .select2-selection__rendered {
        line-height: 40px !important;
    }
    #selectDiagnosisElement + .select2-container .select2-selection__arrow {
        height: 40px !important;
    }

    
    #selectTreatmentElement + .select2-container .select2-selection--single {
        height: 40px !important;
        line-height: 40px !important;
    }
    #selectTreatmentElement + .select2-container .select2-selection__rendered {
        line-height: 40px !important;
    }
    #selectTreatmentElement + .select2-container .select2-selection__arrow {
        height: 40px !important;
    }
    
</style>

    <x-dashboard-container title="Pemeriksaan Pasien">
        @if(session('failed'))
            <?php //dd(session('failed')); ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <strong>Gagal!</strong> {{ session('failed') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <form method="post" action="{{ route('patient.appointment.action', $registration->id) }}">
            @csrf
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th width="10%">ID Registrasi</th>
                        <th width="5%">:</th>
                        <th width="55%">{{ $registration->id }}</th>
                    </tr>
                    <tr>
                        <th width="10%">Tanggal Daftar</th>
                        <th width="5%">:</th>
                        <th width="55%">{{ $registration->createdAt->format('H:i d-m-Y') }}</th>
                    </tr>
                    <tr>
                        <th>Nama Pasien</th>
                        <th>:</th>
                        <th>{{ $registration->patient->name }}</th>
                    </tr>
                    <tr>
                        <th>Umur</th>
                        <th>:</th>
                        <th>{{ $registration->patient->dateOfBirth->age }} Tahun</th>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <th>:</th>
                        <th>{{ $registration->patient->address }}</th>
                    </tr>
                    <!-- <tr>
                        <th>Keluhan</th>
                        <th>:</th>
                        <th>{{ $registration->complaint }}</th>
                    </tr> -->
                </tbody>
            </table>
            <h4>Data Medis Pasien</h4>
            <hr>
            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="keluhan" class="active">
                        <a href="#keluhan" aria-controls="keluhan" role="tab" data-toggle="tab">Keluhan</a>
                    </li>
                    <li role="periksa">
                        <a href="#diagnosa" aria-controls="diagnosa" role="tab" data-toggle="tab">Diagnosa</a>
                    </li>
                    <!--Pada Bagian Obat Ditambah Dosis dan
                        Jumlah Obat yang ada di inventory berkurang setelah ada pemeriksaan -->
                    <li role="periksa">
                        <a  href="#tindakan" 
                            aria-controls="tindakan" 
                            role="tab" 
                            data-toggle="tab">
                                Tindakan (Ops)</a>
                    </li>
                    <li role="periksa">
                        <a href="#terapi" aria-controls="terapi" role="tab" data-toggle="tab">Catatan (Ops)</a>
                    </li>
                    <li role="periksa">
                        <a href="#medicine" aria-controls="medicine" role="tab" data-toggle="tab">Obat (X)</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane" id="diagnosa">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th colspan="2">Input Diagnosa</th>
                            </tr>

                            <tr>
                                <th>
                                    <select name="diagnosa" id="selectDiagnosisElement" class="form-control select2" style="height: 40px;">
                                        @foreach ($diagnoses as $index => $diagnosis)
                                            <option data-icd="{{ $diagnosis->icdCode }}" value="{{ $diagnosis->id }}">
                                                {{ $diagnosis->diseaseName }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <input type="button" name="" value="Tambah" id="submitDiagnosisButton"
                                        class="btn btn-info" style="height:40px;">
                                </th>
                            </tr>
                    </table>
                    <table class="table table-striped">
                        <tbody id="diagnosisTable">
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="tindakan">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th colspan="2">Input Tindakan</th>
                            </tr>

                            <tr>
                                <th>
                                    <select name="diagnosa" id="selectTreatmentElement" class="form-control select2">
                                        @foreach ($treatments as $index => $treatment)
                                            <option data-icd="{{ $treatment->treatmentCode }}"
                                                value="{{ $treatment->id }}">{{ $treatment->treatmentName }}</option>
                                        @endforeach
                                    </select>

                                </th>
                                <th>
                                    <input type="button" name="" value="Tambah" id="submitTreatmentButton"
                                        class="btn btn-info" style="height: 40px;">
                                </th>
                            </tr>
                    </table>
                    <table class="table table-striped">
                        <tbody id="treatmentTable">
                        </tbody>
                    </table>
                </div>

                <div role="tabpanel" class="tab-pane active" id="keluhan">
                    <table class="table">
                        <tr>
                            <th>
                                Keluhan Pasien (bisa ditambahkan jika belum sesuai)
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <textarea name="keluhan" style="min-height: 100px; width: 100%" rows="" cols="">{{ $registration->complaint }}</textarea>
                            </th>
                        </tr>
                    </table>
                </div>

                <div role="tabpanel" class="tab-pane" id="terapi">
                    <table class="table">
                        <tr>
                            <th>
                                Catatan Dokter
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <textarea name="receipt" style="min-height: 100px; width: 100%" rows="" cols=""></textarea>
                            </th>
                        </tr>
                    </table>
                </div>

                <div role="tabpanel" class="tab-pane" id="medicine">

                    <div class="form-group row" style="margin-top : 15px">
                        <label class="col-xs-3">Nama Obat</label>
                        <div class="col-xs-9">
                            <select name="medicine" id="selectMedicineElement" class="form-control select2">
                                @foreach ($medicines as $index => $medicine)
                                    <option data-quantity="{{ $medicine->unit->name }}"
                                        data-icd="{{ $medicine->id }}" value="{{ $medicine->id }}"
                                        data-stock="{{ $medicine->stock }}"
                                        data-unit-name="{{ $medicine->unit->name }}">
                                        {{ $medicine->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    

                    <div class="form-group row">
                        <label class="col-xs-3">Sisa Obat</label>
                        <div class="col-xs-9">
                            <input type="text" name="quantity" class="form-control"
                                        id="medicineStockElement" placeholder="Jumlah Obat" required="required">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-xs-3">Jumlah Obat</label>
                        <div class="col-xs-9">
                            <input type="text" name="quantity" value="1" min="1" class="form-control"
                                        id="inputQuantityElement" placeholder="Jumlah Obat" required="required">
                        </div>
                    </div>

                    <!-- Baris 2 -->
                    <div class="form-group row">
                        <label class="col-xs-3">Dosis</label>
                        <div class="col-xs-9">
                            <input type="text" name="dosis" value="1 tablet" id="inputDosisElement" class="form-control" 
                            placeholder="Contoh: 1 tablet, 5 ml, oles tipis" required="required">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-xs-3">Frekuensi</label>
                        <div class="col-xs-3">
                            <input type="number" name="perhari" value="3" min="1" max="5" 
                            id="inputPerhariElement" class="form-control" required="required">
                        </div>
                        <div class="col-xs-1" style="text-align: center; line-height: 34px; font-weight: bold;">
                            x
                        </div>
                        <div class="col-xs-2" style="line-height: 34px; font-weight: bold;">
                            Sehari
                        </div>
                    <!-- </div>
                        <div class="col-xs-3">
                            <input type="number" name="hari" value="1" min="1"  id="inputHariElement" class="form-control">
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <div class="col-xs-3"></div> <!-- Empty spacer matching label width -->
                        <div class="col-xs-9">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-3"></div> <!-- Empty spacer matching label width -->
                        <div class="col-xs-9">
                            <input type="button" name="" value="Tambah" id="submitMedicineButton"
                                        class="btn btn-info">
                        </div>
                    </div>

                    <table class="table">
                        <tbody>
                            <?php /*
                            <tr>
                                <th colspan="4">Input Obat</th>
                            </tr>
                            <tr>
                                <th>Nama Obat :</th>
                                <th colspan="3">
                                    
                                    <p>
                                        <!-- <span id="medicineStockElement"></span> -->
                                    </p>
                                </th>
                            </tr>
                            <tr>
                                <th>Sisa Obat :</th>
                                <th colspan="3">
                                    <input type="text" name="quantity" class="form-control"
                                        id="medicineStockElement" placeholder="Jumlah Obat" required="required">
                                </th>
                            </tr>
                            <tr>
                                <th>Jumlah :</th>
                                <th colspan="3">
                                    <input type="number" name="quantity" value="1" min="1" class="form-control"
                                        id="inputQuantityElement" placeholder="Jumlah Obat" required="required">
                                </th>
                            </tr>
                            <tr>
                                <th>Dosis :</th>
                                <th>
                                    <input type="number" name="perhari" value="3" min="1" max="5" class="form-control"
                                        id="inputPerhariElement"  required="required">
                                </th>
                                <th>
                                    X
                                </th>
                                <th>
                                    <input type="number" name="hari" value="1" min="1" class="form-control"
                                        id="inputHariElement" required="required">
                                </th>
                            </tr>
                            
                            <tr>
                                <th>
                                    <input type="button" name="" value="Tambah" id="submitMedicineButton"
                                        class="btn btn-info">
                                </th>
                            </tr>*/
                            ?>

                    </table>
                    <table class="table table-striped">
                        <tbody id="medicineTable">
                        </tbody>
                    </table>
                </div>

            </div>

            <div id="dynamic-data">
            </div>

            <div class="well">
                <input type="submit" disabled="true" name="submit" value="Selesai Pemeriksaan" id="submit"
                    class="btn btn-success">
                <a class="btn btn-info" href="{{ route('patient.queue') }}">Kembali</a>
            </div>
        </form>
    </x-dashboard-container>
@endsection

@push('scripts')
    <script>
        class AttachManager {
            constructor(
                submitButton,
                tableElement,
                selectElement,
                inputName
            ) {
                this.submitButton = submitButton
                this.tableElement = tableElement
                this.selectElement = selectElement
                this.inputName = inputName
                this.dynamicDataContainer = document.getElementById("dynamic-data");
                this.selected = null;
                this.counter = 0;
                this.observer = null;
                this.removedOptions = {};

                if (typeof this.isAny === 'function') {
                    this.isAny = this.isAny.bind(this);
                } else {
                    this.isAny = () => this.counter > 0;
                }

                this.submitButton.addEventListener("click", () => {
                    this.submitButtonEventListener();

                    if (this.observer) {
                        this.observer.eventCallback()
                    }
                })
            }

            subscribeTo(observer) {
                this.observer = observer
                this.observer.subscribe(this)
            }

            submitButtonEventListener() {
                this.selected = this.getSelected();

                this.appendData();
                this.appendInputElement();

                const option = this.selectElement.querySelector(`option[value="${this.selected.value}"]`);
                if (option) {
                    this.removedOptions[this.selected.value] = option.outerHTML;
                    option.remove();
                    $(this.selectElement).trigger('change');
                }

                this.counter++;
            }

            getSelected() {
                const selected = {
                    value: this.selectElement.value,
                    text: this.selectElement.options[this.selectElement.selectedIndex].text,
                    icd: this.selectElement.options[this.selectElement.selectedIndex].dataset.icd,
                };

                return selected
            }

            createInputElement(name, value) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                return input;
            }

            createAppendElement() {
                const appendedElement = `
                <tr data-attached-id="${this.selected.value}">
                    <td>${this.selected.icd} - ${this.selected.text}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-danger attach-remove-btn"><i class="glyphicon glyphicon-trash"></i></a>
                    </td>
                </tr>
            `

                return appendedElement;
            }

            appendInputElement() {
                const input = this.createInputElement(`${this.inputName}[]`, this.selected.value);
                input.setAttribute('data-attached-id', this.selected.value);
                this.dynamicDataContainer.appendChild(input);
            }

            appendData() {
                this.tableElement.insertAdjacentHTML("beforeend", this.createAppendElement());

                const attachedValue = this.selected.value;
                const lastRow = this.tableElement.querySelector('tr[data-attached-id="' + attachedValue + '"]');
                if (lastRow) {
                    const btn = lastRow.querySelector('.attach-remove-btn');
                    if (btn) {
                        btn.addEventListener('click', (e) => {
                            e.preventDefault();
                            this.removeAttached(attachedValue, lastRow);
                        });
                    }
                }
            }

            removeAttached(value, rowElement) {
                if (rowElement && rowElement.parentNode) rowElement.parentNode.removeChild(rowElement);

                const inputs = Array.from(this.dynamicDataContainer.querySelectorAll(
                    `input[data-attached-id="${value}"]`));
                inputs.forEach(i => i.parentNode && i.parentNode.removeChild(i));

                const optionHtml = this.removedOptions[value];
                if (optionHtml) {
                    this.selectElement.insertAdjacentHTML('beforeend', optionHtml);
                    delete this.removedOptions[value];
                    $(this.selectElement).trigger('change');
                }

                this.counter = Math.max(0, this.counter - 1);
                if (this.observer) this.observer.eventCallback();
            }
        }

        class MedicineAttachManager extends AttachManager {
            constructor(
                submitButton,
                tableElement,
                selectElement,
                inputName,
                inputQuantity,
                stockElement,
                inputHariElement,
                inputPerhariElement,
                inputDosisElement
            ) {
                super(submitButton, tableElement, selectElement, inputName)
                this.inputQuantity = inputQuantity
                this.stockElement = stockElement

                //Frekuensi
                this.inputPerhariElement = inputPerhariElement

                // Dosis
                this.inputDosisElement = inputDosisElement

                //this.inputHariElement = inputHariElement
                this.medicineSelected = null
                this.bindEventStockListener();

                this.medicineSelected = null
                this.bindEventStockListener();
            }

            getSelected() {
                const selected = {
                    value: this.selectElement.value,
                    text: this.selectElement.options[this.selectElement.selectedIndex].text,
                    icd: this.selectElement.options[this.selectElement.selectedIndex].dataset.icd,
                    stock: Number(this.selectElement.options[this.selectElement.selectedIndex].dataset.stock),
                    unitName: this.selectElement.options[this.selectElement.selectedIndex].dataset.unitName,
                    quantity: Number(this.inputQuantity.value || 0),

                    // Frekuensi
                    perhari: Number(this.inputPerhariElement.value || 0),

                    // Dosis (string)
                    dosis: this.inputDosisElement.value || '',

                    //hari: Number(this.inputHariElement.value || 0), 
                };

                return selected
            }

                // getSelected() Lama tanpa ada variable Dosis Obat
            //getSelected() {
                //const selected = {
                    //value: this.selectElement.value,
                    //text: this.selectElement.options[this.selectElement.selectedIndex].text,
                    //icd: this.selectElement.options[this.selectElement.selectedIndex].dataset.icd,
                    //stock: Number(this.selectElement.options[this.selectElement.selectedIndex].dataset.stock),
                    //unitName: this.selectElement.options[this.selectElement.selectedIndex].dataset.unitName,
                    //quantity: Number(this.inputQuantity.value || 0),
                    
                    // Dosis
                    //perhari: Number(this.inputPerhariElement.value || 0),
                    //hari: Number(this.inputHariElement.value || 0), 
                //};
                //return selected
            //}

            submitButtonEventListener() {
                this.selected = this.getSelected();

                if (this.selected.quantity < 1) {
                    alert("Jumlah obat minimal 1");
                    return;
                }

                const existingIdInput = this.dynamicDataContainer.querySelector(
                    `input[data-medicine-id="${this.selected.value}"]`);

                if (existingIdInput) {
                    const nameMatch = existingIdInput.name.match(/\[([0-9]+)\]\['id'\]$/);
                    const idx = nameMatch ? nameMatch[1] : null;

                    if (idx !== null) {
                        const qtyInput = this.dynamicDataContainer.querySelector(
                            `input[name="${this.inputName}[${idx}]['quantity']"]`);
                        const currentQty = Number(qtyInput.value || 0);
                        const newQty = currentQty + this.selected.quantity;

                        if (newQty > this.selected.stock) {
                            alert("Jumlah obat melebihi stock yang ada");
                            this.inputQuantity.value = '';
                            return;
                        }

                        qtyInput.value = newQty;

                        const row = this.tableElement.querySelector(`tr[data-medicine-id="${this.selected.value}"]`);
                    // If (row) Sebelum Diubah
                        /*if (row) {
                            const qtySpan = row.querySelector('.medicine-qty');
                            if (qtySpan) qtySpan.textContent = newQty;
                            const firstCell = row.querySelector('td');
                            if (firstCell) firstCell.innerHTML =
                                `${this.selected.icd} - ${this.selected.text} | kuantitas: <span class="medicine-qty">${newQty}</span>`;
                        }*/
                    
                        if (row) {
                            const qtySpan = row.querySelector('.medicine-qty');
                            if (qtySpan) {
                                qtySpan.textContent = newQty;
                            }
                            const doseSpan = row.querySelector('.medicine-dose');
                            if (doseSpan) {
                                doseSpan.textContent =
                                    `${this.selected.perhari}x sehari`;
                            }
                        }
                        this.inputQuantity.value = 1;
                        return;
                    }
                }

                if (this.selected.quantity > this.selected.stock) {
                    alert("Jumlah obat melebihi stock yang ada");
                    this.inputQuantity.value = '';
                    return;
                }

                this.appendData();
                this.appendInputElement();
                this.counter++;
                this.inputQuantity.value = 1;
            }

            bindEventStockListener() {
                const changeStock = () => {
                    const selectedOption = this.selectElement.options[this.selectElement.selectedIndex];
                    const stock = selectedOption.dataset.stock;
                    const unitName = selectedOption.dataset.unitName;
                    // this.stockElement.textContent = `Sisa stock: ${stock} ${unitName}`;
                    this.stockElement.value = `${stock} ${unitName}`;
                    this.inputQuantity.max = stock;
                };

                $(this.selectElement).on("change", changeStock);

                changeStock();
            }
                /* AppendInputElement Sebelum Di Ubah Atau yang Asli
            appendInputElement() {
                const idInput = this.createInputElement(`${this.inputName}[${this.counter}]['id']`, this.selected
                    .value);
                idInput.setAttribute('data-medicine-id', this.selected.value);
                idInput.setAttribute('data-attached-id', this.selected.value);
                this.dynamicDataContainer.appendChild(idInput);

                const quantityInput = this.createInputElement(`${this.inputName}[${this.counter}]['quantity']`, this
                    .selected.quantity);
                quantityInput.setAttribute('data-medicine-id', this.selected.value);
                quantityInput.setAttribute('data-attached-id', this.selected.value);
                this.dynamicDataContainer.appendChild(quantityInput);
            } */ 
        
            appendInputElement() {

                // ID Obat
                const idInput = this.createInputElement(
                    `${this.inputName}[${this.counter}]['id']`,
                    this.selected.value
                );
                idInput.setAttribute('data-medicine-id', this.selected.value);
                idInput.setAttribute('data-attached-id', this.selected.value);
                this.dynamicDataContainer.appendChild(idInput);


                // Jumlah Obat
                const quantityInput = this.createInputElement(
                    `${this.inputName}[${this.counter}]['quantity']`,
                    this.selected.quantity
                );
                quantityInput.setAttribute('data-medicine-id', this.selected.value);
                quantityInput.setAttribute('data-attached-id', this.selected.value);
                this.dynamicDataContainer.appendChild(quantityInput);


                // Dosis per hari ()
                const perhariInput = this.createInputElement(
                    `${this.inputName}[${this.counter}]['perhari']`,
                    this.selected.perhari
                );
                perhariInput.setAttribute('data-medicine-id', this.selected.value);
                perhariInput.setAttribute('data-attached-id', this.selected.value);
                this.dynamicDataContainer.appendChild(perhariInput);

                // Dosis Per Minum
                const dosisInput = this.createInputElement(
                    `${this.inputName}[${this.counter}]['dosis']`,
                    this.selected.dosis
                );

                dosisInput.setAttribute('data-medicine-id', this.selected.value);
                dosisInput.setAttribute('data-attached-id', this.selected.value);
                this.dynamicDataContainer.appendChild(dosisInput);


            /*    // Lama penggunaan (hari)
                const hariInput = this.createInputElement(
                    `${this.inputName}[${this.counter}]['hari']`,
                    this.selected.hari
                );
                hariInput.setAttribute('data-medicine-id', this.selected.value);
                hariInput.setAttribute('data-attached-id', this.selected.value);
                this.dynamicDataContainer.appendChild(hariInput); */
            }
            /* createAppendElement Sebelum Di Ubah Atau yang Asli
            createAppendElement() {
                const appendedElement = `
                <tr data-medicine-id="${this.selected.value}" data-attached-id="${this.selected.value}">
                    <td>${this.selected.icd} - ${this.selected.text} | kuantitas: <span class="medicine-qty">${this.selected.quantity}</span></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-danger attach-remove-btn" title="Hapus Obat"><i class="glyphicon glyphicon-trash"></i></a>
                    </td>
                </tr>
            `
                return appendedElement;
            }
        } */

            createAppendElement() {
                const appendedElement = `
                    <tr data-medicine-id="${this.selected.value}" data-attached-id="${this.selected.value}">
                        <td>
                            ${this.selected.text} - ${this.selected.quantity} ${this.selected.unitName}
                            — <span class="medicine-dose">${this.selected.dosis} ${this.selected.perhari}x sehari</span>
                            <td>
                                <a href="#" 
                                class="btn btn-sm btn-danger attach-remove-btn" 
                                title="Hapus Obat">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </td>
                        </td>
                    </tr>
                `;

                return appendedElement;
            }
        }

        class StatusButtonObserver {
            constructor(buttonElement) {
                this.buttonElement = buttonElement
                this.subscribers = []
            }

            subscribe(subscriber) {
                if (subscriber && typeof subscriber.isAny === 'function') {
                    this.subscribers.push(subscriber)
                } else {
                    console.warn('StatusButtonObserver: ignored invalid subscriber', subscriber)
                }
            }

            eventCallback() {
                let isAllAny = true;

                for (const subscriber of this.subscribers) {
                    if (!subscriber || typeof subscriber.isAny !== 'function') {
                        console.warn('StatusButtonObserver: skipping invalid subscriber', subscriber);
                        continue;
                    }

                    try {
                        if (!subscriber.isAny()) {
                            isAllAny = false;
                            break;
                        }
                    } catch (err) {
                        console.error('StatusButtonObserver: subscriber check failed', err);
                        isAllAny = false;
                        break;
                    }
                }

                this.buttonElement.disabled = !isAllAny;
            }
        }


        const diagnosisTable = document.getElementById("diagnosisTable");
        const submitDiagnosisButton = document.getElementById('submitDiagnosisButton');
        const selectDiagnosisElement = document.getElementById("selectDiagnosisElement");

        const diagnosisAttachManager = new AttachManager(submitDiagnosisButton, diagnosisTable, selectDiagnosisElement,
            'diagnosis_ids');

        const treatmentTable = document.getElementById("treatmentTable");
        const submitTreatmentButton = document.getElementById('submitTreatmentButton');
        const selectTreatmentElement = document.getElementById("selectTreatmentElement");

        const treatmentAttachManager = new AttachManager(submitTreatmentButton, treatmentTable, selectTreatmentElement,
            'treatment_ids');

        const medicineTable = document.getElementById("medicineTable");
        const submitMedicineButton = document.getElementById("submitMedicineButton");
        const selectMedicineElement = document.getElementById("selectMedicineElement");
        const stockElement = document.getElementById("medicineStockElement");
        const inputQuantityElement = document.getElementById("inputQuantityElement");
        const inputHariElement = document.getElementById("inputHariElement");
        const inputPerhariElement = document.getElementById("inputPerhariElement");
        const inputDosisElement = document.getElementById("inputDosisElement");

        //asli
        //const medicineAttachManager = new MedicineAttachManager(submitMedicineButton, medicineTable, selectMedicineElement,
        //'medicine_ids', inputQuantityElement, stockElement);
        
        const medicineAttachManager = new MedicineAttachManager(
            submitMedicineButton, 
            medicineTable, 
            selectMedicineElement,
            'medicine_ids', 
            inputQuantityElement, 
            stockElement,
            inputHariElement, // Lama penggunaan (saat ini tidak digunakan)
            inputPerhariElement, // Frekuensi per hari
            inputDosisElement // Dosis (string)
        );
        //ubah disini untuk tambah dosis

        const statusButtonObserver = new StatusButtonObserver(document.getElementById("submit"))

        diagnosisAttachManager.subscribeTo(statusButtonObserver)

        //Nif. Didisable supaya ga harus isi treatment dan obat
        //treatmentAttachManager.subscribeTo(statusButtonObserver)
        //medicineAttachManager.subscribeTo(statusButtonObserver)
    </script>
@endpush
