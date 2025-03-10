<!-- resources/views/rapports/index.blade.php -->
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
                    <!-- Tableau de rapport -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom du Rapport</th>
                                <th>Description</th>
                                <th>Date de Création</th>
                                <th>Résumé</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Données du rapport -->
                            <tr>
                                <td>1</td>
                                <td>Rapport des Ventes</td>
                                <td>Analyse détaillée des ventes mensuelles</td>
                                <td>{{ now()->format('d/m/Y') }}</td>
                                <td>Chiffre d'affaires mensuel : {{ number_format($chiffreAffaires, 2, ',', ' ') }} €</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Rapport des Stocks</td>
                                <td>État des stocks et réapprovisionnements</td>
                                <td>{{ now()->format('d/m/Y') }}</td>
                                <td>Niveau de stock critique : {{ $stockCritique }} produits</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection