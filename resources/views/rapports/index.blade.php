@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rapports et Analyses</h3>
                </div>
                <div class="card-body">
                    <!-- Exemple de tableau de rapport -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom du Rapport</th>
                                <th>Description</th>
                                <th>Date de Création</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Ajoutez vos données de rapport ici -->
                            <tr>
                                <td>1</td>
                                <td>Rapport des Ventes</td>
                                <td>Analyse détaillée des ventes mensuelles</td>
                                <td>{{ now()->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Rapport des Stocks</td>
                                <td>État des stocks et réapprovisionnements</td>
                                <td>{{ now()->format('d/m/Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
